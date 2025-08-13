<?php


// namespace App\Http\Controllers;

// use App\Models\Notice;
// use Illuminate\Http\Request;

// class NoticeController extends Controller
// {
//     public function index()
//     {
//         $notices = Notice::whereDate('publish_date', '<=', now())
//                          ->where(function($q){
//                              $q->whereNull('expiry_date')
//                                ->orWhereDate('expiry_date', '>=', now());
//                          })
//                          ->orderBy('publish_date', 'desc')
//                          ->get();
//         return view('notices.index', compact('notices'));
//     }

//     public function create()
//     {
//         return view('notices.create');
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'title' => 'required',
//             'content' => 'required',
//             'publish_date' => 'required|date',
//             'expiry_date' => 'nullable|date|after_or_equal:publish_date',
//         ]);

//         Notice::create($request->all());

//         return redirect()->route('notices.index')->with('success', 'Notice created successfully.');
//     }
// }

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::latest()->get();
        return view('notices.index', compact('notices'));
    }

    public function create()
    {
        return view('notices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date',
        ]);

        Notice::create($request->all());

        return redirect()->route('notices.index')->with('success', 'Notice created successfully.');
    }

    // public function print($id)
    // {
    //     $notice = Notice::findOrFail($id);
    //     return view('notices.print', compact('notice'));
    // }
    public function print($id)
{
    $notice = Notice::findOrFail($id);
    $companyAddress = "128, Jubilee Road,10th Floor,Kader Tower,Chittagong,40001";
    return view('notices.print', compact('notice', 'companyAddress'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required',
        'publish_date' => 'required|date',
        'expiry_date' => 'nullable|date',
    ]);

    $notice = Notice::findOrFail($id);
    $notice->update($request->all());
    return redirect()->back()->with('success', 'Notice updated successfully!');
}


public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('notices.index')->with('success', 'Notice deleted successfully.');
    }
}

