<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class LabelEditor extends Component
{
    public float $widthCm  = 10.0;
    public float $heightCm = 5.4;
    public array $elements = [];
    public string $zpl     = '';
    public string $preview = '';

    public function mount()
    {
        $this->elements = [
            [
                'type'     => 'text',
                'x'        => 5,
                'y'        => 4.0,
                'height'   => 10.0,
                'width'    => 10.0,
                'value'    => "Descripcion de varias líneas.",
                'showText' => 'below',
            ],
            [
                'type'     => 'barcode',
                'x'        => 20.0,
                'y'        => 25.0,
                'height'   => 20.0,
                'width'    => 0.5,
                'value'    => '12345678',
                'showText' => 'below',
            ],
        ];

        $this->renderizar();
    }

    public function addElement($type)
    {
        $this->elements[] = [
            'type'     => $type,
            'x'        => 10.0,
            'y'        => 10.0,
            'height'   => 5.0,
            'width'    => 20.0,
            'value'    => $type === 'text' ? "Texto..." : "123456",
            'showText' => 'below',
        ];
    }

    public function removeElement($i)
    {
        unset($this->elements[$i]);
        $this->elements = array_values($this->elements);
    }

    public function updated($name)
    {
        if (
            $name === 'widthCm' ||
            $name === 'heightCm' ||
            str_contains($name, 'elements.')
        ) {
            $this->renderizar();
        }
    }

    private function renderizar()
    {
        $dpi     = 203;
        $cm2dots = fn($cm) => round($cm * $dpi / 2.54);
        $mm2dots = fn($mm) => round($mm * 8);

        $dotsW = $cm2dots($this->widthCm);
        $dotsH = $cm2dots($this->heightCm);
        $inchW = round($dotsW / $dpi, 2);
        $inchH = round($dotsH / $dpi, 2);

        $z = "^XA\n"
        . "^CI28\n"
        . "^FH\n"
        . "^PW{$dotsW}^LL{$dotsH}\n";

        foreach ($this->elements as $el) {
            $x = $mm2dots($el['x']);
            $y = $mm2dots($el['y']);
            $h = $mm2dots($el['height']);
            $w = $mm2dots($el['width']);
        
            if ($el['type'] === 'text') {
                $value = str_replace("\n", '\&', $el['value']);
                $blockWidth = $dotsW - $x - 20;
                $maxLines = 10;
                $z .= "^FO{$x},{$y}^A0N,{$h},{$w}" .
                      "^FB{$blockWidth},{$maxLines},0,L^FD{$value}^FS\n";
            } else {
                $show = $el['showText'] === 'below' ? 'Y' : 'N';
                $z .= "^FO{$x},{$y}^BY{$w},3,{$h}^BCN,{$h},{$show},N,N^FD{$el['value']}^FS\n";
            }
        }

        $z .= "^PQ1\n^XZ";
        $this->zpl = $z;

        $url = "https://api.labelary.com/v1/printers/8dpmm/labels/{$inchW}x{$inchH}/0/";
        $png = Http::timeout(8)
            ->withHeaders([
                'Accept'       => 'image/png',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])
            ->withBody($z, 'application/x-www-form-urlencoded')
            ->post($url)
            ->body();

        $this->preview = str_starts_with($png, 'ERROR')
            ? ($this->addError('zpl', $png) && '')
            : 'data:image/png;base64,'.base64_encode($png);
    }

    public function render()
    {
        return view('livewire.label-editor');
    }
}
