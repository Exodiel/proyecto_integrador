<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="card card-chart">
                <div class="card-header">
                  <h4>Ingresos</h4>
                </div>
                <div class="card-content">
                  <div class="ct-chart">
                    <canvas id="ingresos"></canvas>
                  </div>
                </div>
                <div class="card-footer">
                  <p>Ingresos de los últimos meses</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-chart">
                <div class="card-header">
                  <h4>Egresos</h4>
                </div>
                <div class="card-content">
                  <div class="ct-chart">
                    <canvas id="egresos"></canvas>
                  </div>
                </div>
                <div class="card-footer">
                  <p>Egresos de los últimos meses</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
<script>
export default {
  props: ['ruta'],
  data() {
    return {
      ingreso: null,
      charIngreso: null,
      ingresos: [],
      totalIngreso: [],
      mesIngreso: [],

      egreso: null,
      charEgreso: null,
      egresos: [],
      totalEgreso: [],
      mesEgreso: []
    };
  },
  methods: {
    getIngresos() {
      let me = this;
      let url = `${this.ruta}/dashboard`;
      axios
        .get(url)
        .then(response => {
          let respuesta = response.data;
          me.ingresos = respuesta.ingresos;
          //cargamos los datos al chart
          me.loadIngresos();
        })
        .catch(err => {
          console.log(err);
        });
    },
    getEgresos() {
      let me = this;
      let url = `${this.ruta}/dashboard`;
      axios
        .get(url)
        .then(response => {
          let respuesta = response.data;
          me.egresos = respuesta.egresos;
          //cargamos los datos al chart
          me.loadEgresos();
        })
        .catch(err => {
          console.log(err);
        });
    },
    loadIngresos() {
      let me = this;
      me.ingresos.map(x => {
        me.mesIngreso.push(x.mes);
        me.totalIngreso.push(x.total);
      });
      me.ingreso = document.getElementById("ingresos").getContext("2d");
      me.charIngreso = new Chart(me.ingreso, {
        type: "bar",
        data: {
          labels: me.mesIngreso,
          datasets: [
            {
              label: "Ingresos",
              data: me.totalIngreso,
              backgroundColor: "rgba(255, 99, 132, 0.2)",
              borderColor: "rgba(255, 99, 132, 0.2)",
              borderWidth: 1
            }
          ]
        },
        options: {
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true
                }
              }
            ]
          }
        }
      });
    },
    loadEgresos() {
      let me = this;
      me.egresos.map(x => {
        me.mesEgreso.push(x.mes);
        me.totalEgreso.push(x.total);
      });
      me.egreso = document.getElementById("egresos").getContext("2d");
      me.charEgreso = new Chart(me.egreso, {
        type: "bar",
        data: {
          labels: me.mesEgreso,
          datasets: [
            {
              label: "Egresos",
              data: me.totalEgreso,
              backgroundColor: "rgba(54, 162, 235, 0.2)",
              borderColor: "rgba(54, 162, 235, 0.2)",
              borderWidth: 1
            }
          ]
        },
        options: {
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true
                }
              }
            ]
          }
        }
      });
    }
  },
  mounted() {
    this.getIngresos();
    this.getEgresos();
  }
};
</script>
