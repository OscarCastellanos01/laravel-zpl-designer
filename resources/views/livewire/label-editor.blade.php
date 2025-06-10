<div class="container mx-auto p-6">
    <div class="grid grid-cols-5 gap-6">
      {{-- Lateral: tamaño y elementos --}}
      <div class="col-span-5 sm:col-span-2 lg:col-span-1 bg-white shadow rounded-lg p-6 space-y-6">
        <h2 class="font-semibold">Tamaño etiqueta (cm)</h2>
        <div class="grid grid-cols-2 gap-4">
          <label>Ancho
            <input type="number"
                   wire:model.live.debounce.300ms="widthCm"
                   step="0.1"
                   class="mt-1 w-full rounded border-gray-300 focus:ring-indigo-500" />
          </label>
          <label>Alto
            <input type="number"
                   wire:model.live.debounce.300ms="heightCm"
                   step="0.1"
                   class="mt-1 w-full rounded border-gray-300 focus:ring-indigo-500" />
          </label>
        </div>
  
        <hr>
        <h2 class="font-semibold">Elementos</h2>
        @foreach($elements as $i => $el)
          <div wire:key="{{ $i }}" class="border rounded p-4 space-y-2">
            <div class="flex justify-between">
              <span>{{ ucfirst($el['type']) }} #{{ $i+1 }}</span>
              <button wire:click="removeElement({{ $i }})" class="text-red-600">Eliminar</button>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm">
              <label>X (mm)
                <input type="number"
                       wire:model.live.debounce.150ms="elements.{{ $i }}.x"
                       step="1"
                       class="mt-1 w-full rounded border-gray-300 focus:ring-indigo-500" />
              </label>
              <label>Y (mm)
                <input type="number"
                       wire:model.live.debounce.150ms="elements.{{ $i }}.y"
                       step="1"
                       class="mt-1 w-full rounded border-gray-300 focus:ring-indigo-500" />
              </label>
              <label>Altura (mm)
                <input type="number"
                       wire:model.live.debounce.150ms="elements.{{ $i }}.height"
                       step="1"
                       class="mt-1 w-full rounded border-gray-300 focus:ring-indigo-500" />
              </label>
              <label>Ancho (mm)
                <input type="number"
                       wire:model.live.debounce.150ms="elements.{{ $i }}.width"
                       step="1"
                       class="mt-1 w-full rounded border-gray-300 focus:ring-indigo-500" />
              </label>
  
              <label class="col-span-2">Valor
                <textarea
                  wire:model.live.debounce.150ms="elements.{{ $i }}.value"
                  rows="4"
                  class="mt-1 w-full rounded border-gray-300 focus:ring-indigo-500 whitespace-pre-wrap resize-none"></textarea>
              </label>
  
              @if($el['type']==='barcode')
                <label class="col-span-2">Mostrar texto
                  <select wire:model.live.debounce.300ms="elements.{{ $i }}.showText"
                          class="mt-1 w-full rounded border-gray-300 focus:ring-indigo-500">
                    <option value="below">Abajo</option>
                    <option value="none">Oculto</option>
                  </select>
                </label>
              @endif
            </div>
          </div>
        @endforeach
  
        <div class="flex gap-2 justify-center">
          <button wire:click="addElement('text')" class="px-4 py-2 bg-green-500 text-white rounded">+ Texto</button>
          <button wire:click="addElement('barcode')" class="px-4 py-2 bg-blue-500 text-white rounded">+ Código</button>
        </div>
      </div>
  
      {{-- Preview + ZPL --}}
      <div class="col-span-5 sm:col-span-3 lg:col-span-4 bg-white shadow rounded-lg p-6 space-y-6">
        <h2 class="font-semibold">Vista previa</h2>
        <div class="flex justify-center">
          <div class="border-2 border-gray-400 p-2">
            @if($preview)
              <img src="{{ $preview }}" alt="Vista previa" class="block max-w-full" />
            @else
              <span class="text-red-600">No se pudo renderizar</span>
            @endif
          </div>
        </div>

        <hr>
        <label class="font-semibold">ZPL generado</label>
        <textarea rows="15"
            wire:model="zpl"
            readonly
          class="mt-1 w-full font-mono rounded border-gray-300 focus:ring-indigo-500 overflow-auto"></textarea>
      </div>
    </div>
  </div>
  