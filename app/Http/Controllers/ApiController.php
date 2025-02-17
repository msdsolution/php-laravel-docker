<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Job_Service_Cat;
use App\Models\Holiday;
use App\Models\SriLankaDistricts;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Notifications\VerifyEmail;

class ApiController extends Controller
{
    public function testApi()
    {
        // Make a GET request to a sample API (replace with your API endpoint)
        $response = Http::get('https://jsonplaceholder.typicode.com/posts/1');

        // Decode the JSON response
        $data = $response->json();

        // Display the response
        return response()->json($data);
    }

    public function getSignupFormData(Request $request)
    {
        $country_list = [
            'AF' => 'Afghanistan',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CD' => 'Democratic Republic of the Congo',
            'CG' => 'Republic of Congo',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'HR' => 'Croatia (Hrvatska)',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'TL' => 'East Timor',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'FX' => 'France, Metropolitan',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GG' => 'Guernsey',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard and Mc Donald Islands',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'IM' => 'Isle of Man',
            'ID' => 'Indonesia',
            'IR' => 'Iran (Islamic Republic of)',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'CI' => 'Ivory Coast',
            'JE' => 'Jersey',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea, Democratic People\'s Republic of',
            'KR' => 'Korea, Republic of',
            'XK' => 'Kosovo',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macau',
            'MK' => 'North Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States of',
            'MD' => 'Moldova, Republic of',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestine',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'SS' => 'South Sudan',
            'GS' => 'South Georgia South Sandwich Islands',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SH' => 'St. Helena',
            'PM' => 'St. Pierre and Miquelon',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen Islands',
            'SZ' => 'Eswatini',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania, United Republic of',
            'TH' => 'Thailand',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States minor outlying islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City State',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'VG' => 'Virgin Islands (British)',
            'VI' => 'Virgin Islands (U.S.)',
            'WF' => 'Wallis and Futuna Islands',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        ];

        $new_country_list = array_values($country_list);

        $srilanka_cities = SriLankaDistricts::with('cities')
                    ->get();

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Records retrieved successfully.',
            'country_list' => $new_country_list,
            'srilanka_provinces' => $srilanka_cities,
        ], 200);

    }

    public function register(Request $request)
    {
        if (User::where('email', $request['email'])->exists()) {
            return  response()->json(['success' => false,'message' => 'The email address is already registered.']);
        }

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'location' => 'required',
            'city_id' => 'required',
            'password' => 'required|min:6',
            'user_type' => 'required',
            'phone_no' => 'required',
            'user_address' => 'required',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'location' => $request->input('location'),
            'city_id' => $request->input('city_id'),
            'password' => Hash::make($request->input('password')),
            'user_type' => $request->input('user_type'),
            'status' => $request->input('user_type') == 2 ? 1 : 0, // Default status is set to 0
            'phone_no' => $request->input('phone_no'),
            'user_address' => $request->input('user_address'),
        ]);

        // Send email verification link
        //$user->notify(new VerifyEmail);

        return response()->json(['status' => 201, 'success' => true,'message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 400,
                 'success' => false,
                 'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
     return $credentials;
            return response()->json([
                'status' => 500,
                 'success' => false,
                 'message' => 'Could not create token.',
                ], 500);
        }
  
        if (auth()->user()->status == 1 && (auth()->user()->user_type == 2 || auth()->user()->user_type == 3)) {
            //Token created, return with success response and jwt token
            return response()->json([
                'status' => 200,
                'success' => true,
                'token' => $token,
                'data' => auth()->user(),
             ]);
        } else {
            return response()->json([
                'status' => 401,
                'success' => false,
                'message' => "User is not verified",
             ]);
        }
   
    }

    public function user()
    {
        return response()->json(Auth::user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['status' => 200, 'message' => 'Successfully logged out']);
    }


    

    public function checkDateStatus($selectedDate)
	{
	    //$selectedDate = Carbon::parse($selectedDate);
	    $carbonInstance = Carbon::createFromFormat('M d, Y', $selectedDate);
	    $exists = Holiday::where('date', $carbonInstance->format('M d, Y'))->exists();

	    //dd($carbonInstance->format('M d, Y'));

	    // Check holiday
	    if ($exists) {
	        return response()->json(['status' => 200, 'message' => 'success', 'data' => 'holiday'], 201);
	    }

	    // Check weekend (Saturday or Sunday)
	    if ($carbonInstance->isWeekend()) {
	        return response()->json(['status' => 200, 'message' => 'success', 'data' => 'weekend'], 201);
	    }

	    // weekday
	    return response()->json(['status' => 200, 'message' => 'success', 'data' => 'weekday'], 201);
	}
}