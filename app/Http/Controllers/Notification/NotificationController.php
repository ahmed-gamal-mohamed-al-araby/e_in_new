<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Notification;
use App\Models\NotificationComment;
use App\Models\PurchaseOrder;
use DateTime;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() // show all not viewed notifications
    {
        $allNotifications = Notification::where('view_status', 0)->where('archive', 0)->orderBy('updated_at', 'DESC')->get();
        $viewed = false;
        return view('pages.notification.index', compact('allNotifications', 'viewed'));
    }

    public function viewed() // show all viewed notifications
    {
        $allNotifications = Notification::where('view_status', 1)->where('archive', 0)->orderBy('updated_at', 'DESC')->get();
        $viewed = true;
        return view('pages.notification.index', compact('allNotifications', 'viewed'));
    }

    public function filtrationView()
    {
        $allNotifications = [];
        $request = [];
        return view('pages.notification.filtration', compact('allNotifications', 'request'));
    }

    public function filtration(Request $request)
    {
        $allNotifications = [];
        if ($request->table_name == 'purchase_orders') {
            $purchaseOrderIds = PurchaseOrder::where('purchase_order_reference', 'like', '%' . $request->reference . '%')->pluck('id')->toArray();
            $allNotifications = Notification::where('table_name', 'purchase_orders')->whereIn('record_id', $purchaseOrderIds)->get();
        } else if ($request->table_name == 'documents') {
            $documentIds = Document::where('document_number', 'like', '%' .  $request->reference . '%')->pluck('id')->toArray();
            $allNotifications = Notification::where('table_name', 'documents')->whereIn('record_id', $documentIds)->get();
        } else {
            return redirect()->route('notification.filtrationView');
        }
        $request = $request->except('_token');
        return view('pages.notification.filtration', compact('allNotifications', 'request'));
    }

    public function archiveView()
    {
        return view('pages.notification.archive');
    }

    public function archive(Request $request)
    {
        $from_date = new DateTime($request->from_date);
        // $from_date->modify("-1 second");

        $to_date = new DateTime($request->to_date);
        $to_date->modify("+23 hour + 59 min + 59 second");

        Notification::where('created_at', '>=', $from_date)->where('created_at', '<=', $to_date)->update(['archive' => 1]);
        return redirect()->route('notification.index');
    }
    


    public function changeViewStatus($id) // change notification status from not viewed to viewed
    {
        $notification = Notification::findOrFail($id);
        $notification->update([
            'view_status' => 1,
            'type' => 'n',
            'content' => $notification->content . '<br><b>' . auth()->user()->username . '</b>' . ' شاهد هذا الاشعار',
        ]);
        return redirect()->route('notification.viewed');
    }

    public function reply(Request $request) // set comment on notification that its type is a: for action notification as Add, Edit
    {
        // dd($request->all());
        $notification = Notification::findOrFail($request->n_id);

        $comment = null;
        $content = $request->comment;
        // if ($request->comment) {
            $comment = NotificationComment::create([
                'content' => $content?? '<div class="text-danger">Empty comment</div>',
            ]);
            $comment = $comment->id;

            $notification->update([
                'view_status' => 0,
                'type' => 'n',
                'content' => $notification->content . '<br><b>' . auth()->user()->username . '</b>' . ' علق على هذا الاشعار',
                'notification_comment_id' => $comment,
            ]);
            return redirect()->route('notification.index');
        // } else {
        //     $notification->update([
        //         'view_status' => 1,
        //         'type' => 'n',
        //         'content' => $notification->content . '<br><b>' . auth()->user()->username . '</b>' . ' شاهد هذا الاشعار',
        //     ]);
        //     return redirect()->route('notification.viewed');
        // }
    }
}
