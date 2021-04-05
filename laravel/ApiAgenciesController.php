<?php

namespace App\Http\Controllers\Api;

use App\Agency;
use App\Agency_city;
use App\City;
use App\Helpers\Constants;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiAgenciesController extends ApiController
{

    public function agenciesGet()
    {
        $user = $this->getLoginUser();
        $agencies = Agency::all();
        $favorites = [];
        foreach ($user->favoriteAgencies as $fv) {
            $favorites[$fv->agency_id] = true;
        }
        $agencies = $agencies->map(function ($agency) use ($favorites) {
            return self::getAgencyData($agency, $favorites);
        });
        $data = [
            'agencies' => $agencies,
            'listData' => Agency::getListData(),
        ];
        return $this->json_response($data);
    }

    public function agencyGet(Request $request)
    {
        if ($request->id !== 'undefined') {
            $agency_id = clean($request->id);
            $agency = self::getAgencyData($agency_id);
            $data = [
                'agency' => $agency,
                'listData' => Agency::getListData(),
            ];
        } else {
            $data = [
                'listData' => Agency::getListData()
            ];
        }
        return $this->json_response($data);
    }

    public function agencyStore(Request $request)
    {
        $agency_id = clean($request->id);
        $this->canUserEditAgency($agency_id);

        $agency = Agency::findOrNew($agency_id);

        $agency->user_id = $this->user->id;
        $agency->name = $request->name;
        $agency->city = $request->city;
        $agency->website = $request->website;
        $agency->address = $request->address;
        $agency->email = $request->email;
        $agency->phone = $request->phone;
        $agency->fax = $request->fax;
        $agency->instagram = $request->instagram;
        $agency->save();

        if (!$agency_id) {
            $user = User::find($agency->user_id);
            $user->agency_id = $agency->id;
            $user->save();
            Agency::addUserToAgency($agency->id, $agency->user_id, 'owner');
        }

        $requestCities = clean($request->cities);
        if (is_array($requestCities)) {
            foreach($requestCities as $city){
                $city_id =  !empty ($city['id']) ? $city['id'] : null;
                $agencyCity= Agency_city::findOrNew($city_id);

                $agencyCity->agency_id = $agency->id;
                $agencyCity->city_id = $city['city_id'];
                $agencyCity->website = $city['website'];
                $agencyCity->address = $city['address'];
                $agencyCity->email = $city['email'];
                $agencyCity->phone = $city['phone'];
                $agencyCity->fax = $city['fax'];
                $agencyCity->save();
            }
        }

        $agency = self::getAgencyData($agency->id);
        $data = ['agency' => $agency];
        return $this->json_response($data);
    }

    public function agencyImageUpload(Request $request)
    {
        $agency_id = clean($request->id);
        $agency = Agency::find($agency_id);

        $imageFile = $request->file('file');
        $file_path = 'agencies/agency' . $agency->id . '/images/';

        // TODO:: save images to storage
        if (!empty($imageFile)) {
            Storage::disk(Constants::PUBLIC_USER_STORAGE)->delete($file_path . $agency->image);
            $agency->image = Storage::disk(Constants::PUBLIC_USER_STORAGE)->putFile($file_path, $imageFile);
            // TODO:: check first char of image name '/'
            $agency->image = substr($agency->image, strlen($file_path));
        } else {
            $agency->image = $request->image ?? '';
        }
        $agency->save();
        $agency = self::getAgencyData($agency_id);
        $data = ['agency' => $agency];
        return $this->json_response($data);
    }

    public function agencyImageDelete(Request $request)
    {
        $agency_id = clean($request->id);
        $agency = Agency::find($agency_id);

        $file_path = 'agencies/agency' . $agency->id . '/images/';
        Storage::disk(Constants::PUBLIC_USER_STORAGE)->delete($file_path . $agency->image);
        $agency->image = '';
        $agency->save();
        $agency = self::getAgencyData($agency_id);
        $data = ['agency' => $agency];
        return $this->json_response($data);
    }

    public static function getAgencyData($agency_id, $favorites = null)
    {
        if (is_object($agency_id)) {
            $agency = $agency_id;
        } else {
            $agency = Agency::with('cities.citiesTable')->find($agency_id);
        }
        $agency->imageUrl = Agency::getImgUrl($agency);

        if (!empty($agency->cities)) {
            foreach ($agency->cities as $c) {
                $c->city = $c->citiesTable && $c->citiesTable->city ? $c->citiesTable->city : null;
            }
        }

        if ($favorites) {
            $agency->favorite = isset($favorites[$agency->id]);
        } else {
            if ($agency->id) {
                $agency->favorite = Agency::getFavorite($agency->id, Auth::id());
            } else {
                $agency->favorite = false;
            }
        }
        return $agency;
    }

    public function agencySetFavorite(Request $request)
    {
        $favorite = clean($request->favorite);
        $agency_id = clean($request->agencyID);
        $user = $this->getLoginUser();
        if ($favorite) {
            Agency::addToFavorite($agency_id, $user->id);
        } else {
            Agency::removeFromFavorite($agency_id, $user->id);
        }
        $favorite = Agency::getFavorite($agency_id, $user->id);
        $data = ['favorite' => $favorite];
        return $this->json_response($data);
    }

    public function citySave(Request $request)
    {
        // $this->validator($request->all())->validate();
        $city = new City;
        $city->city = clean($request->cityName);
        $city->top_city = $request->topCity ? clean($request->topCity) : false;
        $city->country_code = clean($request->countryCode);;
        $city->save();

        $cities = $city->id ? City::all() : '';
        $data = ['cities' => $cities];
        return $this->json_response($data);
    }

    /*protected function validator(array $data)
    {
        return Validator::make($data, [
            'cityName' => ['required', 'string', 'max:255', 'unique:cities'],
        ]);
    }*/
}
