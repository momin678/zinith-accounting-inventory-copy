<?php

namespace App\Http\Controllers\backend;

use App\CostCenterType;
use App\Http\Controllers\Controller;
use App\Imports\PartyInfoImport;
use App\PartyInfo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Importer;

class PartyInfoController extends Controller
{
    public function partyInfoDetails()
    {
        $latest = PartyInfo::withTrashed()->latest()->first();

        if ($latest) {
            $pi_code=preg_replace('/^PI-/', '', $latest->pi_code );
            ++$pi_code;
        } else {
            $pi_code = 1;
        }
        if($pi_code<10)
        {
            $cc="PI-000".$pi_code;
        }
        elseif($pi_code<100)
        {
            $cc="PI-00".$pi_code;
        }
        elseif($pi_code<1000)
        {
            $cc="PI-0".$pi_code;
        }
        else
        {
            $cc="PI-".$pi_code;
        }
        $costTypes=CostCenterType::get();
        $partyInfos = PartyInfo::where('pi_type','!=', "Draft")->latest()->paginate(25);
        return view('backend.partyInfo.partyCenterDetails', compact('partyInfos','costTypes','cc'));
    }

    public function partyInfoPost(Request $request)
    {
        $request->validate([
            'pi_name' => 'required',
            'pi_type'        => 'required',
            'trn_no'        => 'required',

        ],
        [
            'pi_name.required' => 'Cost Center is required',
            'pi_type.required' => 'Type is required',
            'trn_no.required' => 'TRN No is required',
        ]
        );

            $latest = PartyInfo::withTrashed()->latest()->first();

            if ($latest) {
                $pi_code=preg_replace('/^PI-/', '', $latest->pi_code );
                ++$pi_code;
            } else {
                $pi_code = 1;
            }
            if($pi_code<10)
            {
                $c_code="PI-000".$pi_code;
            }
            elseif($pi_code<100)
            {
                $c_code="PI-00".$pi_code;
            }
            elseif($pi_code<1000)
            {
                $c_code="PI-0".$pi_code;
            }
            else
            {
                $c_code="PI-".$pi_code;
            }

            $draftCost = new PartyInfo();
            $draftCost->pi_code = $c_code;
        $draftCost->pi_name = $request->pi_name;
        $draftCost->pi_type = $request->pi_type;
        $draftCost->trn_no = $request->trn_no;
        $draftCost->address = $request->address;
        $draftCost->con_person = $request->con_person;
        $draftCost->con_no = $request->con_no;
        $draftCost->phone_no = $request->phone_no;
        $draftCost->email = $request->email;
        $sv=$draftCost->save();

        return redirect()->route('partyInfoDetails')->with('success', 'Added Successfully');
    }


    public function partyInfoEdit($pInfo)
    {
        $partyInfo=PartyInfo::find($pInfo);
        if(!$partyInfo)
        {
            return back()->with('error', "Not Found");

        }
        $costTypes=CostCenterType::get();

        $partyInfos = PartyInfo::where('pi_type','!=', "Draft")->latest()->paginate(25);
        return view('backend.partyInfo.partyCenterDetailsEdit', compact('partyInfos', 'partyInfo','costTypes'));
    }

    public function partyInfoUpdate(Request $request, $costCenter)
    {
        $request->validate([
            'pi_name' => 'required',
            'pi_type'        => 'required',
            'trn_no'        => 'required',

        ],
        [
            'pi_name.required' => 'Cost Center is required',
            'pi_type.required' => 'Type is required',
            'trn_no.required' => 'TRN No is required',
        ]
    );

    $partyInfo=PartyInfo::find($costCenter);
        if(!$partyInfo)
        {
            return back()->with('error', "Not Found");

        }

        $partyInfo->pi_name = $request->pi_name;
        $partyInfo->pi_type = $request->pi_type;
        $partyInfo->trn_no = $request->trn_no;
        $partyInfo->address = $request->address;
        $partyInfo->con_person = $request->con_person;
        $partyInfo->con_no = $request->con_no;
        $partyInfo->phone_no = $request->phone_no;
        $partyInfo->email = $request->email;
        $partyInfo->save();
        return back()->with('success', 'Updated Successfully');
    }


    public function partyInfoDelete($pInfo)
    {
        $partyInfo=PartyInfo::find($pInfo);
        if(!$partyInfo)
        {
            return back()->with('error', "Not Found");

        }
        $partyInfo->forceDelete();
        return redirect()->route('partyInfoDetails')->with('success', "Deleted Successfully");
    }



    public function partyInfoForm(Request $request)
    {
        $latest = PartyInfo::withTrashed()->latest()->first();

        if ($latest) {
            $pi_code=preg_replace('/^PI-/', '', $latest->pi_code );
            ++$pi_code;
        } else {
            $pi_code = 1;
        }
        if($pi_code<10)
        {
            $c_code="PI-000".$pi_code;
        }
        elseif($pi_code<100)
        {
            $c_code="PI-00".$pi_code;
        }
        elseif($pi_code<1000)
        {
            $c_code="PI-0".$pi_code;
        }
        else
        {
            $c_code="PI-".$pi_code;
        }
        $costTypes=CostCenterType::get();


        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.form.partyInfoForm', ['cc' => $c_code,'costTypes' => $costTypes,])->render()]);
        }
    }
    // work by mominul
    public function partyInfo_import(Request $request)
    {
        $request->validate([
            'file' => "required"
        ]);
        $save = Excel::import(new PartyInfoImport, request()->file('file'));
        $notification= array(
            'message'       => 'Party Info Added successfully!',
            'alert-type'    => 'success'
        );
        return redirect('party-info')->with($notification);
    }

}
