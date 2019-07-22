@extends('principal')
@section('contenido')

@if (Auth::check())

<template v-if="menu==0">
  <dashboard :ruta="ruta"></dashboard>
</template>
<template v-if="menu==1">
  <categoria :ruta="ruta"></categoria>
</template>
<template v-if="menu==2">
  <item :ruta="ruta"></item>
</template>
<template v-if="menu==3">
  <ingreso :ruta="ruta"></ingreso>
</template>
<template v-if="menu==4">
  <proveedor :ruta="ruta"></proveedor>
</template>
<template v-if="menu==5">
  <egreso :ruta="ruta"></egreso>
</template>
<template v-if="menu==6">
  <user :ruta="ruta"></user>
</template>
<template v-if="menu==7">
  <h1>Ayuda</h1>
</template>
<template v-if="menu==8">
  <h1>Acerca de...</h1>
</template>

@endif


@endsection
