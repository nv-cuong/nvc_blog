<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->get();
        return view('admin.subscriber', compact('subscribers'));
    }

    public function delete($id)
    {
         $subscriber = Subscriber::findOrFail($id);
         $subscriber->delete();
         Toastr::success('Subscriber successfully deleted', 'Success');
         return redirect()->route('admin.subscriber.index');
    }
}
