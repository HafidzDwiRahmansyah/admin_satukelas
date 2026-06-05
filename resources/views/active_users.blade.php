@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 text-white p-8">

    <!-- HEADER -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
        <div>
            <h1 class="text-3xl font-bold flex items-center gap-3">
                <i class="fa-solid fa-user-clock text-emerald-400"></i>
                Active Users Monitoring
            </h1>
            <p class="text-gray-400 text-sm mt-1">
                Real-time activity dashboard
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-4">

            <input type="text" id="searchUser"
                placeholder="Search user..."
                class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">

            <select id="companyFilter"
                class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-sm"
                onchange="applyFilter()">
                <option value="all">All Companies</option>
            </select>

            <button onclick="manualRefresh()"
                class="bg-emerald-500 hover:bg-emerald-600 px-4 py-2 rounded-lg shadow transition">
                <i class="fa-solid fa-rotate mr-2"></i> Refresh
            </button>

        </div>
    </div>

    <!-- SUMMARY -->
    <div class="grid md:grid-cols-3 gap-6 mb-10">

        <div class="bg-slate-800 p-6 rounded-2xl shadow border border-slate-700">
            <div class="text-gray-400 text-sm">Total Active Users</div>
            <div id="totalUsers" class="text-3xl font-bold text-emerald-400 mt-2">0</div>
        </div>

        <div class="bg-slate-800 p-6 rounded-2xl shadow border border-slate-700">
            <div class="text-gray-400 text-sm">Total Companies</div>
            <div id="totalCompanies" class="text-3xl font-bold text-indigo-400 mt-2">0</div>
        </div>

        <div class="bg-slate-800 p-6 rounded-2xl shadow border border-slate-700">
            <div class="text-gray-400 text-sm">Online Now (&lt; 2 min)</div>
            <div id="onlineNow" class="text-3xl font-bold text-green-400 mt-2">0</div>
        </div>

    </div>

    <!-- CHARTS -->
    <div class="grid lg:grid-cols-2 gap-6 mb-12">

        <div class="bg-slate-800 p-6 rounded-2xl shadow border border-slate-700">
            <h3 class="font-semibold mb-4">Users per Company</h3>
            <canvas id="barChart"></canvas>
        </div>

        <div class="bg-slate-800 p-6 rounded-2xl shadow border border-slate-700">
            <h3 class="font-semibold mb-4">Distribution</h3>
            <canvas id="pieChart"></canvas>
        </div>

    </div>

    <!-- USER GRID -->
    <div id="usersContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

</div>


<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

let allUsers = [];
let barChart, pieChart;

async function fetchUsers() {
    const response = await fetch('/active-users/data');
    const users = await response.json();

    allUsers = users;
    renderDashboard(users);
    populateCompanyFilter(users);
    renderCharts(users);
}

function renderDashboard(users) {

const container = document.getElementById('usersContainer');
container.innerHTML = '';

const now = Date.now();
let onlineCount = 0;

// === FILTER MAX 20 MENIT ===
let filteredUsers = users
    .map(user => {
        const lastActivity = new Date(user.last_activity);
        const diffMinutes = Math.floor((now - lastActivity) / 60000);

        let status = 'Offline';
        let color = 'text-gray-400';
        let priority = 3; // default paling bawah

        if (diffMinutes < 2) {
            status = 'Online';
            color = 'text-green-400';
            priority = 1;
            onlineCount++;
        } else if (diffMinutes < 5) {
            status = 'Idle';
            color = 'text-yellow-400';
            priority = 2;
        } else if (diffMinutes <= 60) {
            status = 'Offline';
            color = 'text-gray-400';
            priority = 3;
        } else {
            return null; // >20 menit tidak ditampilkan
        }

        return {
            ...user,
            diffMinutes,
            status,
            color,
            priority
        };
    })
    .filter(user => user !== null);

// === SORTING ===
filteredUsers.sort((a, b) => {
    if (a.priority !== b.priority) {
        return a.priority - b.priority; // Online > Idle > Offline
    }
    return new Date(b.last_activity) - new Date(a.last_activity); // terbaru di atas
});

// === UPDATE SUMMARY ===
document.getElementById('totalUsers').innerText = filteredUsers.length;

const companies = [...new Set(filteredUsers.map(u => u.user?.company ?? '-'))];
document.getElementById('totalCompanies').innerText = companies.length;
document.getElementById('onlineNow').innerText = onlineCount;

// === RENDER CARD ===
filteredUsers.forEach(user => {

    const company = user.user?.company ?? '-';

    const card = `
        <div class="bg-slate-800 p-6 rounded-2xl shadow border border-slate-700 hover:scale-105 transition">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-user text-emerald-400"></i>
                    ${user.user?.name ?? 'Unknown'}
                </h2>
                <span class="text-xs font-semibold ${user.color}">
                    ${user.status}
                </span>
            </div>

            <div class="text-sm text-gray-300 space-y-2">
                <div><i class="fa-solid fa-building mr-2 text-indigo-400"></i>${company}</div>
                <div><i class="fa-solid fa-globe mr-2 text-blue-400"></i>${user.ip_address ?? '-'}</div>
            </div>

            <div class="mt-4 text-right text-xs text-gray-400">
                ${user.diffMinutes} min ago
            </div>
        </div>
    `;

    container.innerHTML += card;
});
}

function populateCompanyFilter(users) {

    const select = document.getElementById('companyFilter');
    const companies = [...new Set(users.map(u => u.user?.company ?? '-'))];

    select.innerHTML = '<option value="all">All Companies</option>';

    companies.forEach(company => {
        select.innerHTML += `<option value="${company}">${company}</option>`;
    });
}

function applyFilter() {

    const selected = document.getElementById('companyFilter').value;
    const search = document.getElementById('searchUser').value.toLowerCase();

    let filtered = allUsers;

    if (selected !== 'all') {
        filtered = filtered.filter(u => (u.user?.company ?? '-') === selected);
    }

    if (search) {
        filtered = filtered.filter(u => 
            (u.user?.name ?? '').toLowerCase().includes(search)
        );
    }

    renderDashboard(filtered);
    renderCharts(filtered);
}

function renderCharts(users) {

const companyCount = {};

users.forEach(user => {
    const company = user.user?.company ?? '-';
    companyCount[company] = (companyCount[company] || 0) + 1;
});

const labels = Object.keys(companyCount);
const data = Object.values(companyCount);

const total = data.reduce((a, b) => a + b, 0);

if (barChart) barChart.destroy();
if (pieChart) pieChart.destroy();

// BAR CHART
const ctxBar = document.getElementById('barChart');
barChart = new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Active Users',
            data: data
        }]
    }
});

// PIE CHART (WITH PERCENTAGE TOOLTIP)
const ctxPie = document.getElementById('pieChart');
pieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: data
        }]
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {

                        const value = context.raw;
                        const percentage = ((value / total) * 100).toFixed(1);

                        return `${context.label}: ${value} users (${percentage}%)`;
                    }
                }
            },
            legend: {
                labels: {
                    color: 'white'
                }
            }
        }
    }
});
}

function manualRefresh() {
    fetchUsers();
}

document.getElementById('searchUser').addEventListener('keyup', applyFilter);

setInterval(fetchUsers, 10000);
fetchUsers();

</script>

@endsection