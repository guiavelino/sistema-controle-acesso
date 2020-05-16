/*Gráfico anual*/
let myChartByYear = document.getElementById('myChartByYear').getContext('2d');
let chartByYear = new Chart(myChartByYear, {
 
    type: 'line',

    data: {
        labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto','Setembro','Outubro','Novembro','Dezembro'],
        datasets: [{
            label: 'Registro de entrada geral',
			pointBackgroundColor:'#fff',
			pointBorderColor:'#5d78ff',
			pointHitRadius:40,
			borderColor:'#5d78ff',
			backgroundColor: 'rgba(93, 120, 255,0.0)',
            data: [50, 25, 15, 22, 40, 60, 70, 64, 50, 20, 50, 100]
        }]
    },

    options: {
		maintainAspectRatio:false,
		legend: {
			display: false,
		},
	}
});

/*Gráfico semanal*/
let myWeeklyChart = document.getElementById('myWeeklyChart').getContext('2d');
let WeeklyChart = new Chart(myWeeklyChart, {
 
    type: 'doughnut',
  
    data: {
		labels: ['Visitantes','Prestadores s.', 'Encomendas'],	
        datasets: [{
            label: 'Registro de entrada semanal',
            backgroundColor: ['#4e73df', '#36b9cc','#20c9a6'],
            data: [50, 30, 20],
        }]	
    },
	
    options: {
		maintainAspectRatio:false,	
		cutoutPercentage: '80', 
	
		 layout: {
            padding: {
                left: 38,
                right: 38,
                bottom: 10,
            },
        },
        
        legend: {
            display: true, 
            position:'bottom',
            align: 'start',
			labels: {
				boxWidth: 12,
                fontSize: 16,	
            },
        },
	}
});
