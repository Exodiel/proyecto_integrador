<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reporte de Kardex</title>
  <style>
    body {
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      font-size: 0.875rem;
      font-weight: normal;
      line-height: 1.5;
      color: #151b1e;
    }

    .table {
      display: table;
      width: 100%;
      max-width: 100%;
      margin-bottom: 1rem;
      background-color: transparent;
      border-collapse: collapse;
    }

    .table-bordered {
      border: 1px solid #c2cfd6;
    }

    thead {
      display: table-header-group;
      vertical-align: middle;
      border-color: inherit;
    }

    tr {
      display: table-row;
      vertical-align: inherit;
      border-color: inherit;
    }

    .table th,
    .table td {
      padding: 0.75rem;
      vertical-align: top;
      border-top: 1px solid #c2cfd6;
    }

    .table thead th {
      vertical-align: bottom;
      border-bottom: 2px solid #c2cfd6;
    }

    .table .thead-dark th {
      color: #fff;
      background-color: #343a40;
      border-color: #454d55;
    }

    .table-bordered thead th,
    .table-bordered thead td {
      border-bottom-width: 2px;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid #c2cfd6;
    }

    th,
    td {
      display: table-cell;
      vertical-align: inherit;
    }

    th {
      font-weight: bold;
      text-align: -internal-center;
      text-align: left;
    }

    tbody {
      display: table-row-group;
      vertical-align: middle;
      border-color: inherit;
    }

    tr {
      display: table-row;
      vertical-align: inherit;
      border-color: inherit;
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0, 0, 0, 0.05);
    }


  </style>
</head>

<body>
  <div>
    <h3>Kardex </h3>
    <span>{{now()}}</span>
  </div>
  <div>
    <table class="table table-bordered table-striped table-sm">
      <thead class="thead-dark">
        <tr>
          <th>Descripción</th>
          <th>Fecha Hora</th>
          <th>Entrada Cantidad</th>
          <th>Entrada Precio Unit.</th>
          <th>Entrada V.Total</th>
          <th>Salida Cantidad</th>
          <th>Salida Precio Unit.</th>
          <th>Salida V.Total</th>
          <th>Existencia Cantidad</th>
          <th>Existencia Precio Unit.</th>
          <th>Existencia V.Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($transacciones as $t)
        <tr>
          <td>{{$t->detalle}}</td>
          <td>{{$t->fecha_hora}}</td>
          <td>{{$t->entrada_cantidad}}</td>
          <td>{{$t->entrada_precio_unitario}}</td>
          <td>{{$t->entrada_valor_total}}</td>
          <td>{{$t->salida_cantidad}}</td>
          <td>{{$t->salida_precio_unitario}}</td>
          <td>{{$t->salida_valor_total}}</td>
          <td>{{$t->exis_cantidad}}</td>
          <td>{{$t->exis_precio_unitario}}</td>
          <td>{{$t->exis_valor_total}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</body>

</html>
