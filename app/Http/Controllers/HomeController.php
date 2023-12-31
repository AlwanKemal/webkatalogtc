<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Sparepart;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\DisabledDate;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $vehicle = "";
        $count = 0;
        $history = "";
        if (auth()->user()->role_id == 1) {
            $vehicle = Vehicle::all();
            $count = Vehicle::count();
            $history = Booking::where('status', 'done')->get();
        }else if(auth()->user()->role_id == 2){
            $vehicle = Vehicle::where('id_user', auth()->user()->id)->get();
            $history = Booking::where('id_user', auth()->user()->id)->where('status', 'done')->get();
            if ($vehicle->isEmpty() && $history->isEmpty()) {
                $vehicle = "";
                $count = 0;
                $history = "";
            }else if($vehicle->isEmpty() && !$history->isEmpty()){
                $vehicle = "";
                $count = 0;
                $history = Booking::where('id_user', auth()->user()->id)->where('status', 'done')->get();
            }else if(!$vehicle->isEmpty() && $history->isEmpty()){
                $vehicle = Vehicle::where('id_user', auth()->user()->id)->get();
                $count = Vehicle::where('id_user', auth()->user()->id)->count();
                $history = "";
            }
            else{
                $vehicle = Vehicle::where('id_user', auth()->user()->id)->get();
                $count = Vehicle::where('id_user', auth()->user()->id)->count();
                $history = Booking::where('id_user', auth()->user()->id)->where('status', 'done')->get();
            }
        }
        $countCarRepair = Booking::where('service_type', 'repair')
            ->where('status', 'on_process')
            ->where('vehicle_type', 'car')
            ->count();
        $countCarWash = Booking::where('service_type', 'wash')
            ->where('status', 'on_process')
            ->where('vehicle_type', 'car')
            ->count();
        $countMotorcycleRepair = Booking::where('service_type', 'repair')
            ->where('status', 'on_process')
            ->where('vehicle_type', 'motorcycle')
            ->count();
        $countMotorcycleWash = Booking::where('service_type', 'wash')
            ->where('status', 'on_process')
            ->where('vehicle_type', 'motorcycle')
            ->count();
        return view('homepage_view.home', compact('vehicle', 'count', 'history', 'countCarRepair', 'countCarWash', 'countMotorcycleRepair', 'countMotorcycleWash'));
    }

    public function deleteHistory($id)
    {
        $history = Booking::find($id);
        $history->delete();
        return redirect()->route('home');
    }

    public function orderlist()
    {
        $orderlist = Booking::orderBy('status', 'asc')
            ->orderByRaw("FIELD(status, 'stand_by', 'on_process', 'done')")
            ->get();
        return view('homepage_view.orderlist', compact('orderlist'));
    }

    public function orderUser($id)
    {
        $orderlist = Booking::with(['user'])->where('id_user', $id)->get();
        return view('homepage_view.orderUser', compact('orderlist'));
    }

    public function deleteBooking($id)
    {
        $orderlist = Booking::find($id);
        $orderlist->delete();
        return redirect()->route('home');
    }

    public function sparepart()
    {
        $sparepart = Sparepart::all();
        return view('homepage_view.sparepart', compact('sparepart'));
    }

    public function invoice()
    {
        $orderlist = Booking::with('user')->get();
        return view('homepage_view.invoice', compact('orderlist'));
    }

    public function invoiceUser($id)
    {
        $orderlist = Booking::with(['user', 'spareparts'])->where('id_user', $id)->where('status', 'done')->get();
        return view('homepage_view.invoice', compact('orderlist'));
    }

    public function profile($id)
    {
        $user = User::find($id);
        return view('homepage_view.profil', compact('user'));
    }

    public function disableDateIndex()
    {
        $disabledDates = DisabledDate::all();
        return view('homepage_view.disableDate', compact('disabledDates'));
    }

    public function disableDateStore(Request $request)
    {
        $request->validate([
            'disabled_date' => 'required|date',
        ]);

        $disabledDate = DisabledDate::where('disabled_date', $request->disabled_date)->first();

        if ($disabledDate) {
            return redirect()->back()->with('warning', 'The selected date is already disabled due to: ' . $disabledDate->description);
        }

        DisabledDate::create([
            'disabled_date' => $request->disabled_date,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Date Disabled!');
    }

    public function disableDateDestroy($id)
    {
        $disabledDate = DisabledDate::findOrFail($id);
        $disabledDate->delete();

        return redirect()->back()->with('success', 'Date Enabled!');
    }

    public function disableDateUpdate(Request $request, $id)
    {
        $request->validate([
            'edit_description' => 'required',
        ]);

        $disabledDate = DisabledDate::findOrFail($id);
        $disabledDate->description = $request->edit_description;
        $disabledDate->save();

        return redirect()->back()->with('success', 'Description updated successfully!');
    }

}
