<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Storage;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * @var Request
     */
    private $request;

    /**
     * FormController constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Show form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('form', ['total_value' => 0]);
    }

    /**
     * Handle post request.
     *
     * @return array
     */
    public function post()
    {
        if ($this->request->ajax()) {
            $this->validate($this->request, [
                'product_name' => 'required',
                'quantity'     => 'required|numeric',
                'price'        => 'required|numeric'
            ]);

            $data = $this->request->except('_token');
            $data['date_time'] = date('Y-m-d H:i:s');
            $data['quantity'] = (int)$this->request->quantity;
            $data['price'] = (int)$this->request->price;
            $data['total_value_number'] = (int)$this->request->quantity * $this->request->price;

            Storage::put('form-data/' . date('Y-m-d H:i:s') . '.json', json_encode($data));

            return [
                'status' => 'success',
                'html'   => '<tr id="' . $data['date_time'] . '"><th>'
                    . $data['product_name'] . '</th><th>'
                    . $data['quantity'] . '</th><th>'
                    . $data['price'] . '</th><th><time datetime="'
                    . $data['date_time'] . '"></time>'
                    . Carbon::parse($data['date_time'])->diffForHumans() . '</th><th>'
                    . $data['total_value_number'] . '</th></tr>'
            ];
        }
    }

    /**
     * Update a line of data.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update()
    {
        $data = $this->request->except('method', '_token');
        $file_path = 'form-data/' . $data['date_time'] . '.json';

        if (Storage::exists($file_path)) {
            $data['date_time'] = date('Y-m-d H:i:s');
            $data['total_value_number'] = (int)$this->request->quantity * $this->request->price;
            Storage::put($file_path, json_encode($data));
        }

        return redirect(route('form'));
    }
}
