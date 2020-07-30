<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectErpItem extends Component
{
    public $data;
    public $name;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $data = json_decode(file_get_contents('https://app.pmui.co.id/api/v1/item'));
        return view('components.select-erp-item', ['data'=>$data]);
    }
}
