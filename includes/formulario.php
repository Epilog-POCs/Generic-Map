
<div class="container">
  <div class="row">
    <div class="form-group col-12">
    <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseFormulario">
      <i class="fas fa-filter"></i>
    </button>
    </div>    
  </div>
  <div class="row collapse" id="collapseFormulario">
    <div class="form-group col-12">
      <label>Busca un Lugar</label>
      <input id="lugarBuscadoMapaPrincipal" type="text" class="form-control form-control-sm bg-light border-0 small" placeholder="Busca un lugar"/>
    </div>
    <div class="form-group col-12">
      <label>Tipo</label>
      <select class="form-control form-control-sm">
        <option value="" selected disabled>Seleccione una opción</option>
        <option value="puntos">Puntos</option>
        <option value="ventas">Ventas</option>
        <option value="inventario">Inventario</option>
      </select>
    </div>
    <div class="form-group col-12">
      <label>Categorías</label>
      <select class="form-control form-control-sm">
        <option>Todas</option>
        <option>Categoria 1</option>
        <option>Categoria 2</option>
        <option>Categoria 3</option>
        <option>Categoria 4</option>
        <option>Categoria 5</option>
        <option>Categoria 6</option>
      </select>
    </div>
    <div class="form-group col-12">
      <label>Subcategorías</label>
      <select class="form-control form-control-sm">
        <option>Todas</option>
        <option>Subcategoría 1</option>
        <option>Subcategoría 2</option>
        <option>Subcategoría 3</option>
        <option>Subcategoría 4</option>
        <option>Subcategoría 5</option>
        <option>Subcategoría 6</option>
      </select>
    </div>
    <div class="form-group col-12">
      <label>SKUs</label>
      <select class="form-control form-control-sm">
        <option>Todos</option>
        <option>SKU 0000000001</option>
        <option>SKU 0000000002</option>
        <option>SKU 0000000003</option>
        <option>SKU 0000000004</option>
        <option>SKU 0000000005</option>
        <option>SKU 0000000006</option>
        <option>SKU 0000000007</option>
        <option>SKU 0000000008</option>
        <option>SKU 0000000009</option>
        <option>SKU 0000000010</option>
        <option>SKU 0000000011</option>
        <option>SKU 0000000012</option>
        <option>SKU 0000000013</option>
        <option>SKU 0000000014</option>
        <option>SKU 0000000015</option>
        <option>SKU 0000000016</option>
        <option>SKU 0000000017</option>
        <option>SKU 0000000018</option>
        <option>SKU 0000000019</option>

      </select>
    </div>
    <div class="form-group col-6">
      <label>Desde</label>
      <input type="date" class="form-control form-control-sm"/>
    </div>
    <div class="form-group col-6">
      <label>Hasta</label>
      <input type="date" class="form-control form-control-sm"/>
    </div>
    <div class="form-group col-12">
      <button type="button" class="btn btn-primary btn-block" onclick="aleatorizarEstados()" id="aleatorizar">
        Filtrar
      </button>
    </div>
  </div>
</div>