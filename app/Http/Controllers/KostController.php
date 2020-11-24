<?php

namespace App\Http\Controllers;

use App\Kost;
use App\Owner;
use APP\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KostController extends Controller
{
    public function index()
    {
        $kost = Kost::all();
        return response()->json($kost,200);
    }

    public function detail(Request $request)
    {
        $kost = Kost::find($request->id);
        return response()->json($kost,200);
    }

    public function search(Request $request)
    {
        $kost = Kost::where('name', 'LIKE', '%' . $request->search . '%')
        ->orWhere('location', 'LIKE', '%' . $request->search  . '%')
        ->orWhere('price', 'LIKE', '%' . $request->search  . '%')
        ->orderBy('id', 'desc')
        ->paginate(10);
        return response()->json($kost,200);
    }

    public function sorted(Request $request)
    {
        if($request->order == 'asc'){
            $kost = Kost::orderBy('price','asc')->get();
            return response()->json($kost,200);
        }else if($request->order == 'desc'){
            $kost = Kost::orderByDesc('price')->get();
            return response()->json($kost,200);
        }else{
            return response([
                'status' => 'Data not found',
                'message' => 'Data not found',
            ],404);
        }
        
    }

    public function show(Request $request)
    {
        $id = Auth::user()->id;
        $kost = Kost::where('owner_id',$id)
        ->orderBy('id', 'desc')->get();

        return response()->json($kost,200);
    }

    public function create(Request $request)
    {
        $insertKost = new Kost;
        $insertKost->name = $request->name;
        $insertKost->price = $request->price;
        $insertKost->location = $request->location;
        $insertKost->desc = $request->desc;
        $insertKost->availability = $request->availability;
        $insertKost->owner_id = Auth::user()->id;
        $insertKost->save();

        return response([
            'status' => 'OK',
            'message' => 'Kost Created',
            'data' => $insertKost
        ],200);
    }

    public function update(Request $request, $id)
    {
        $check_kost = Kost::firstWhere('id', $id);
        if($check_kost){
            $dataKost = Kost::find($id);
            $dataKost->name = $request->name;
            $dataKost->price = $request->price;
            $dataKost->location = $request->location;
            $dataKost->desc = $request->desc;
            $dataKost->availability = $request->availability;
            $dataKost->save();
            return response([
                'status' => 'OK',
                'message' => 'Kost Updated',
                'data' => $dataKost
            ],200);
        } else {
            return response([
                'status' => 'Not found',
                'message' => 'Kost not found',
            ],404);
        }
    }
    
    public function delete(Request $request, $id)
    {
        $check_kost = Kost::firstWhere('id', $id);
        if($check_kost){
            Kost::destroy($id);
            return response([
                'status' => 'OK',
                'message' => 'Kost Deleted'
            ],200);
        } else {
            return response([
                'status' => 'Data not found',
                'message' => 'Kost not found',
            ],404);
        }
    }
    public function ask(Request $request, $id)
    {
        $kost = Kost::find($id);
        if($kost){
            $id_user = Auth::user()->id;
            $datacredit = User::find($id_user);
            $credit = $datacredit->credit - 5;
            $datacredit->credit = $credit; 
            $datacredit->save();
            return response([
                'status' => 'OK',
                'availability' => $kost->availability,
                'credit' => $credit,

            ],200);
        } else {
            return response([
                'status' => 'Data not found',
                'message' => 'Kost not found',
            ],404);
        }
    }

}
