import Chart from 'chart.js/auto';


document.addEventListener("DOMContentLoaded", function(){

    const toggle = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    if(toggle){
        toggle.addEventListener('click', function(){
            sidebar.classList.toggle('collapsed');
        });
    }

    // Treeview
    document.querySelectorAll('.has-tree > .menu-link')
    .forEach(link => {
        link.addEventListener('click', function(e){
            e.preventDefault();
            this.parentElement.classList.toggle('open');
        });
    });

    // Chart
    if(document.getElementById('kunjunganChart')){
        const ctx = document.getElementById('kunjunganChart');
        const labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kunjungan',
                    data: labels.map((_,i)=> window.chartData?.[i+1] ?? 0)
                }]
            }
        });
    }

});
