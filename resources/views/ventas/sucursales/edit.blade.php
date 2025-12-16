@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Editar Sucursal</h1>
            <a href="{{ route('sucursales.index') }}"
                class="text-slate-500 hover:text-slate-700 text-sm font-medium transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('sucursales.update', $sucursal) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6">
                    <!-- Cliente -->
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-slate-700 mb-1">Cliente</label>
                        <select name="cliente_id" id="cliente_id"
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            required>
                            <option value="">Seleccione un cliente...</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ (old('cliente_id', $sucursal->cliente_id) == $cliente->id) ? 'selected' : '' }}>
                                    {{ $cliente->razon_social }} ({{ $cliente->identificacion }})
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-slate-700 mb-1">Nombre de la
                            Sucursal</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $sucursal->nombre) }}"
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="Ej: Sucursal Centro, Bodega Principal" required>
                        @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label for="direccion" class="block text-sm font-medium text-slate-700 mb-1">Dirección
                            Completa</label>
                        <textarea name="direccion" id="direccion" rows="3"
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="Calle, Edificio, Número, Corregimiento..."
                            required>{{ old('direccion', $sucursal->direccion) }}</textarea>
                        @error('direccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contacto -->
                        <div>
                            <label for="contacto" class="block text-sm font-medium text-slate-700 mb-1">Nombre
                                Contacto</label>
                            <input type="text" name="contacto" id="contacto"
                                value="{{ old('contacto', $sucursal->contacto) }}"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                placeholder="Persona encargada">
                            @error('contacto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
                            <input type="text" name="telefono" id="telefono"
                                value="{{ old('telefono', $sucursal->telefono) }}"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                placeholder="Teléfono de contacto">
                            @error('telefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all">
                        Actualizar Sucursal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection