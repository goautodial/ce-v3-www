<?php
########################################################################################################
####  Name:             	go_carriers_ce.php                                                  ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Christopher Lomuntad				                    ####
####  Modified by:       	Franco E. Hora  				                    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_carriers_ce extends Controller {
	var $userLevel;

	function __construct()
	{
		parent::Controller();
		#parent::Model();
		$this->load->model(array('go_auth','go_dashboard','go_carriers','go_access','gouser'));
		$this->load->library(array('session','pagination','commonhelper'));
		$this->load->helper(array('date','form','url','path'));
		$this->lang->load('userauth', $this->session->userdata('ua_language'));

		$this->userLevel = $this->session->userdata('users_level');
		$config['enable_query_strings'] = FALSE;

            	$this->db = $this->load->database('dialerdb', true);
            	$this->godb = $this->load->database('goautodialdb', true);
	}

	function index()
	{
		$this->is_logged_in();
		if ($this->userLevel < 9) { die('Error: You do not have permission to view this page.'); }
		$data['cssloader'] = 'go_dashboard_cssloader.php';
		$data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
		$data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = $this->lang->line('go_carriers_banner');
		$data['sys']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);


		$data['userfulname'] = $this->go_carriers->go_get_userfulname();
		
		$data['carriers'] = $this->go_carriers->go_get_carrier_list();

		$data['go_main_content'] = 'go_settings/go_carriers';
		$this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_get_carrier()
	{
		$type = $this->uri->segment(3);
		$carrier = $this->uri->segment(4);
		
		switch($type)
		{
			case "modify":
				break;
			
			default:
				$query = $this->db->query("SELECT * FROM vicidial_server_carriers WHERE carrier_id='$carrier';");
				$data['carrier_info'] = $query->row();
				
				$prefixes = explode("\n",$data['carrier_info']->dialplan_entry);
				$prefix = explode(",",$prefixes[0]);
				$prefixuse = substr(ltrim($prefix[0],"exten => _ "),0,(strpos(".",$prefix[0]) - 1));
				
				$this->db->where('dial_prefix',$prefixuse);
				$data['campaigns'] = $this->db->get('vicidial_campaigns')->result();
				break;
		}
		
		$data['templates'] = $this->go_carriers->go_list_templates();
		$data['servers'] = $this->go_carriers->go_list_server_ips();
		
		$data['type'] = $type;
		$this->load->view('go_settings/go_carrier_view',$data);
	}

        function go_carrier_type(){
             $this->load->view('go_settings/go_carrier_wizard_type');
        }

        function go_carrier_sippy_display(){

           $country = array('AFG'=> 'Afghanistan',
                            'ALB'=> 'Albania',
                            'DZA'=> 'Algeria',
			    'ASM'=> 'American Samoa',
			    'AND'=> 'Andorra',
			    'AGO'=> 'Angola',
			    'AIA'=> 'Anguilla',
			    'ATA'=> 'Antarctica',
			    'ATG'=> 'Antigua And Barbuda',
			    'ARG'=> 'Argentina',
			    'ARM'=> 'Armenia',
			    'ABW'=> 'Aruba',
			    'AUS'=> 'Australia',
			    'AUT'=> 'Austria',
			    'AZE'=> 'Azerbaijan',
			    'BHS'=> 'Bahamas',
			    'BHR'=> 'Bahrain',
			    'BGD'=> 'Bangladesh',
			    'BRB'=> 'Barbados',
			    'BLR'=> 'Belarus',
			    'BEL'=> 'Belgium',
			    'BLZ'=> 'Belize',
			    'BEN'=> 'Benin',
			    'BMU'=> 'Bermuda',
			    'BTN'=> 'Bhutan',
			    'BOL'=> 'Bolivia',
			    'BIH'=> 'Bosnia And Herzegovina',
			    'BWA'=> 'Botswana',
			    'BVT'=> 'Bouvet Island',
		            'BRA'=> 'Brazil',
			    'IOT'=> 'British Indian Ocean Territory',
			    'BRN'=> 'Brunei Darussalam',
			    'BGR'=> 'Bulgaria',
			    'BFA'=> 'Burkina Faso',
			    'BDI'=> 'Burundi',
			    'KHM'=> 'Cambodia',
			    'CMR'=> 'Cameroon',
			    'CAN'=> 'Canada',
			    'CPV'=> 'Cape Verde',
			    'CYM'=> 'Cayman Islands',
			    'CAF'=> 'Central African Republic',
		            'TCD'=> 'Chad',
			    'CHL'=> 'Chile',
			    'CHN'=> 'China',
			    'CXR'=> 'Christmas Island',
			    'CCK'=> 'Cocos (Keeling); Islands',
	                    'COL'=> 'Colombia',
			    'COM'=> 'Comoros',
			    'COG'=> 'Congo',
			    'COD'=> 'Congo, The Democratic Republic Of The',
			    'COK'=> 'Cook Islands',
			    'CRI'=> 'Costa Rica',
		            'HRV'=> 'Croatia',
			    'CUB'=> 'Cuba',
			    'CYP'=> 'Cyprus',
			    'CZE'=> 'Czech Republic',
			    'DNK'=> 'Denmark',
			    'DJI'=> 'Djibouti',
			    'DMA'=> 'Dominica',
			    'DOM'=> 'Dominican Republic',
			    'ECU'=> 'Ecuador',
			    'EGY'=> 'Egypt',
			    'SLV'=> 'El Salvador',
			    'GNQ'=> 'Equatorial Guinea',
			    'ERI'=> 'Eritrea',
			    'EST'=> 'Estonia',
			    'ETH'=> 'Ethiopia',
			    'FLK'=> 'Falkland Islands (Malvinas)',
			    'FRO'=> 'Faroe Islands',
			    'FJI'=> 'Fiji',
			    'FIN'=> 'Finland',
			    'FRA'=> 'France',
			    'GUF'=> 'French Guiana',
			    'PYF'=> 'French Polynesia',
			    'ATF'=> 'French Southern Territories',
			    'GAB'=> 'Gabon',
			    'GMB'=> 'Gambia',
			    'GEO'=> 'Georgia',
			    'DEU'=> 'Germany',
			    'GHA'=> 'Ghana',
			    'GIB'=> 'Gibraltar',
			    'GRC'=> 'Greece',
			    'GRL'=> 'Greenland',
			    'GRD'=> 'Grenada',
			    'GLP'=> 'Guadeloupe',
			    'GUM'=> 'Guam',
			    'GTM'=> 'Guatemala',
			    'GIN'=> 'Guinea',
			    'GNB'=> 'Guinea-Bissau',
			    'GUY'=> 'Guyana',
			    'HTI'=> 'Haiti',
			    'HMD'=> 'Heard Island And McDonald Islands',
			    'VAT'=> 'Holy See (Vatican City State)',
			    'HND'=> 'Honduras',
			    'HKG'=> 'Hong Kong',
			    'HUN'=> 'Hungary',
			    'ISL'=> 'Iceland',
			    'IND'=> 'India',
			    'IDN'=> 'Indonesia',
			    'IRN'=> 'Iran, Islamic Republic Of',
			    'IRQ'=> 'Iraq',
			    'IRL'=> 'Ireland',
			    'ISR'=> 'Israel',
			    'ITA'=> 'Italy',
			    'JAM'=> 'Jamaica',
			    'JPN'=> 'Japan',
			    'JOR'=> 'Jordan',
			    'KAZ'=> 'Kazakhstan',
			    'KEN'=> 'Kenya',
			    'KIR'=> 'Kiribati',
			    'PRK'=> "Korea, Democratic People's Republic Of",
			    'KOR'=> 'Korea, Republic of',
			    'KWT'=> 'Kuwait',
			    'KGZ'=> 'Kyrgyzstan',
			    'LAO'=> "Lao People's Democratic Republic",
		            'LVA'=> 'Latvia',
			    'LBN'=> 'Lebanon',
			    'LSO'=> 'Lesotho',
			    'LBR'=> 'Liberia',
			    'LBY'=> 'Libyan Arab Jamahiriya',
			    'LIE'=> 'Liechtenstein',
			    'LTU'=> 'Lithuania',
			    'LUX'=> 'Luxembourg',
			    'MAC'=> 'Macao',
			    'MKD'=> 'Macedonia, The Former Yugoslav Republic Of',
			    'MDG'=> 'Madagascar',
			    'MWI'=> 'Malawi',
			    'MYS'=> 'Malaysia',
			    'MDV'=> 'Maldives',
			    'MLI'=> 'Mali',
			    'MLT'=> 'Malta',
			    'MHL'=> 'Marshall islands',
			    'MTQ'=> 'Martinique',
			    'MRT'=> 'Mauritania',
			    'MUS'=> 'Mauritius',
                            'MYT'=> 'Mayotte',
			    'MEX'=> 'Mexico',
			    'FSM'=> 'Micronesia, Federated States Of',
			    'MDA'=> 'Moldova, Republic Of',
			    'MCO'=> 'Monaco',
			    'MNG'=> 'Mongolia',
			    'MSR'=> 'Montserrat',
			    'MAR'=> 'Morocco',
			    'MOZ'=> 'Mozambique',
		            'MMR'=> 'Myanmar',
			    'NAM'=> 'Namibia',
			    'NRU'=> 'Nauru',
			    'NPL'=> 'Nepal',
			    'NLD'=> 'Netherlands',
			    'ANT'=> 'Netherlands Antilles',
			    'NCL'=> 'New Caledonia',
		            'NZL'=> 'New Zealand',
			    'NIC'=> 'Nicaragua',
			    'NER'=> 'Niger',
			    'NGA'=> 'Nigeria',
			    'NIU'=> 'Niue',
			    'NFK'=> 'Norfolk Island',
			    'MNP'=> 'Northern Mariana Islands',
			    'NOR'=> 'Norway',
			    'OMN'=> 'Oman',
			    'PAK'=> 'Pakistan',
			    'PLW'=> 'Palau',
			    'PSE'=> 'Palestinian Territory, Occupied',
			    'PAN'=> 'Panama',
			    'PNG'=> 'Papua New Guinea',
			    'PRY'=> 'Paraguay',
			    'PER'=> 'Peru',
			    'PHL'=> 'Philippines',
			    'PCN'=> 'Pitcairn',
			    'POL'=> 'Poland',
			    'PRT'=> 'Portugal',
			    'PRI'=> 'Puerto Rico',
			    'QAT'=> 'Qatar',
			    'REU'=> 'Reunion',
			    'ROU'=> 'Romania',
			    'RUS'=> 'Russian Federation',
			    'RWA'=> 'Rwanda',
			    'SHN'=> 'SaINT Helena',
			    'KNA'=> 'SaINT Kitts And Nevis',
			    'LCA'=> 'SaINT Lucia',
			    'SPM'=> 'SaINT Pierre And Miquelon',
		            'VCT'=> 'SaINT Vincent And The Grenadines',
			    'WSM'=> 'Samoa',
			    'SMR'=> 'San Marino',
			    'SAU'=> 'Saudi Arabia',
			    'SEN'=> 'Senegal',
			    'SYC'=> 'Seychelles',
			    'SLE'=> 'Sierra Leone',
			    'SGP'=> 'Singapore',
			    'SVK'=> 'Slovakia',
			    'SVN'=> 'Slovenia',
			    'SLB'=> 'Solomon Islands',
			    'SOM'=> 'Somalia',
			    'ZAF'=> 'South Africa',
			    'SGS'=> 'South Georgia And The South Sandwich Islands',
			    'ESP'=> 'Spain',
			    'LKA'=> 'Sri Lanka',
		            'SDN'=> 'Sudan',
			    'SUR'=> 'Suriname',
			    'SJM'=> 'Svalbard and Jan Mayen',
			    'SWZ'=> 'Swaziland',
			    'SWE'=> 'Sweden',
			    'CHE'=> 'Switzerland',
			    'SYR'=> 'Syrian Arab Republic',
			    'TWN'=> 'Taiwan, Province Of China',
			    'TJK'=> 'Tajikistan',
			    'TZA'=> 'Tanzania, United Republic Of',
			    'THA'=> 'Thailand',
			    'TLS'=> 'Timor-Leste',
			    'TGO'=> 'Togo',
			    'TKL'=> 'Tokelau',
			    'TON'=> 'Tonga',
			    'TTO'=> 'Trinidad And Tobago',
			    'TUN'=> 'Tunisia',
			    'TUR'=> 'Turkey',
			    'TKM'=> 'Turkmenistan',
			    'TCA'=> 'Turks And Caicos Islands',
			    'TUV'=> 'Tuvalu',
		            'UGA'=> 'Uganda',
			    'UKR'=> 'Ukraine',
			    'ARE'=> 'United Arab Emirates',
			    'GBR'=> 'United Kingdom',
			    'USA'=> 'United States',
			    'UMI'=> 'United States Minor Outlying Islands',
			    'URY'=> 'Uruguay',
			    'UZB'=> 'Uzbekistan',
			    'VUT'=> 'Vanuatu',
			    'VEN'=> 'Venezuela',
			    'VNM'=> 'Vietnam',
			    'VGB'=> 'Virgin Islands, British',
			    'VIR'=> 'Virgin Islands, U.S.',
			    'WLF'=> 'Wallis And Futuna',
			    'ESH'=> 'Western Sahara',
  			    'YEM'=> 'Yemen',
			    'YUG'=> 'Yugoslavia',
			    'ZMB'=> 'Zambia',
			    'ZWE'=> 'Zimbabwe',
			    'ASC'=> 'Ascension Island',
			    'DGA'=> 'Diego Garcia',
			    'XNM'=> 'Inmarsat',
			    'TMP'=> 'East timor',
			    'AK'=> 'Alaska',
			    'HI'=> 'Hawaii',
			    'CIV'=> "CÌÄ®ÕÌâå«te d'Ivoire",
			    'ALA'=> 'Aland Islands',
		            'BLM'=> 'Saint Barthelemy',
			    'GGY'=> 'Guernsey',
		            'IMN'=> 'Isle of Man',
			    'JEY'=> 'Jersey',
			    'MAF'=> 'Saint Martin',
			    'MNE'=> 'Montenegro, Republic of',
			    'SRB'=> 'Serbia, Republic of',
			    'CPT'=> 'Clipperton Island',
			    'TAA'=> 'Tristan da Cunha',
                            );
            $data['country'] = $country;


            $tzs = array(
	    '97 ' => 'Africa/Abidjan',					
	    '145' => 'Africa/Accra',
	    '129' => 'Africa/Addis_Ababa',
	    '119' => 'Africa/Algiers',
	    '125' => 'Africa/Asmera',
	    '213' => 'Africa/Bamako',
	    '94 ' => 'Africa/Bangui',
	    '150' => 'Africa/Banjul',
	    '158' => 'Africa/Bissau',
	    '226' => 'Africa/Blantyre',
	    '95 ' => 'Africa/Brazzaville',
	    '46 ' => 'Africa/Bujumbura',
	    '123' => 'Africa/Cairo',
	    '206' => 'Africa/Casablanca',
	    '127' => 'Africa/Ceuta',
	    '151' => 'Africa/Conakry',
	    '300' => 'Africa/Dakar',
	    '322' => 'Africa/Dar_es_Salaam',
	    '115' => 'Africa/Djibouti',
	    '101' => 'Africa/Douala',
	    '124' => 'Africa/El_Aaiun',
	    '298' => 'Africa/Freetown',
	    '66 ' => 'Africa/Gaborone',
	    '369' => 'Africa/Harare',
	    '367' => 'Africa/Johannesburg',
	    '327' => 'Africa/Kampala',
	    '290' => 'Africa/Khartoum',
	    '286' => 'Africa/Kigali',
	    '92 ' => 'Africa/Kinshasa',
	    '242' => 'Africa/Lagos',
	    '139' => 'Africa/Libreville',
	    '310' => 'Africa/Lome',
	    '9  ' => 'Africa/Luanda',
	    '93 ' => 'Africa/Lubumbashi',
	    '368' => 'Africa/Lusaka',
	    '153' => 'Africa/Malabo',
	    '237' => 'Africa/Maputo',
	    '201' => 'Africa/Maseru',
	    '306' => 'Africa/Mbabane',
	    '301' => 'Africa/Mogadishu',
	    '200' => 'Africa/Monrovia',
	    '180' => 'Africa/Nairobi',
	    '308' => 'Africa/Ndjamena',
	    '240' => 'Africa/Niamey',
	    '221' => 'Africa/Nouakchott',
	    '43 ' => 'Africa/Ouagadougou',
	    '47 ' => 'Africa/Porto-Novo',
	    '303' => 'Africa/Sao_Tome',
	    '205' => 'Africa/Tripoli',
	    '315' => 'Africa/Tunis',
	    '238' => 'Africa/Windhoek',
	    '350' => 'America/Adak',
	    '346' => 'America/Anchorage',
	    '5  ' => 'America/Anguilla',
	    '4  ' => 'America/Antigua',
	    '55 ' => 'America/Araguaina',
	    '19 ' => 'America/Argentina/Buenos_Aires',
	    '23 ' => 'America/Argentina/Catamarca',
	    '21 ' => 'America/Argentina/Cordoba',
	    '22 ' => 'America/Argentina/Jujuy',
	    '24 ' => 'America/Argentina/Mendoza',
	    '37 ' => 'America/Aruba',
	    '269' => 'America/Asuncion',
	    '40 ' => 'America/Barbados',
	    '52 ' => 'America/Belem',
	    '68 ' => 'America/Belize',
	    '60 ' => 'America/Boa_Vista',
	    '107' => 'America/Bogota',
	    '342' => 'America/Boise',
	    '81 ' => 'America/Cambridge_Bay',
	    '228' => 'America/Cancun',
	    '357' => 'America/Caracas',
	    '144' => 'America/Cayenne',
	    '191' => 'America/Cayman',
	    '339' => 'America/Chicago',
	    '232' => 'America/Chihuahua',
	    '108' => 'America/Costa_Rica',
	    '58 ' => 'America/Cuiaba',
	    '8  ' => 'America/Curacao',
	    '90 ' => 'America/Dawson',
	    '87 ' => 'America/Dawson_Creek',
	    '341' => 'America/Denver',
	    '332' => 'America/Detroit',
	    '117' => 'America/Dominica',
	    '84 ' => 'America/Edmonton',
	    '62 ' => 'America/Eirunepe',
	    '304' => 'America/El_Salvador',
	    '53 ' => 'America/Fortaleza',
	    '71 ' => 'America/Glace_Bay',
	    '148' => 'America/Godthab',
	    '72 ' => 'America/Goose_Bay',
	    '307' => 'America/Grand_Turk',
	    '142' => 'America/Grenada',
	    '152' => 'America/Guadeloupe',
	    '156' => 'America/Guatemala',
	    '120' => 'America/Guayaquil',
	    '159' => 'America/Guyana',
	    '70 ' => 'America/Halifax',
	    '109' => 'America/Havana',
	    '233' => 'America/Hermosillo',
	    '335' => 'America/Indiana/Indianapolis',
	    '337' => 'America/Indiana/Knox',
	    '336' => 'America/Indiana/Marengo',
	    '338' => 'America/Indiana/Vevay',
	    '86 ' => 'America/Inuvik',
	    '77 ' => 'America/Iqaluit',
	    '177' => 'America/Jamaica',
	    '347' => 'America/Juneau',
	    '333' => 'America/Kentucky/Louisville',		
	    '334' => 'America/Kentucky/Monticello',
	    '50 ' => 'America/La_Paz',
	    '253' => 'America/Lima',
	    '345' => 'America/Los_Angeles',
	    '56 ' => 'America/Maceio',
	    '243' => 'America/Managua',
	    '61 ' => 'America/Manaus',
	    '220' => 'America/Martinique',
	    '231' => 'America/Mazatlan',
	    '340' => 'America/Menominee',
	    '229' => 'America/Merida',
	    '227' => 'America/Mexico_City',
	    '261' => 'America/Miquelon',
	    '230' => 'America/Monterrey',
	    '352' => 'America/Montevideo',
	    '73 ' => 'America/Montreal',
	    '222' => 'America/Montserrat',
	    '64 ' => 'America/Nassau',
	    '331' => 'America/New_York',
	    '74 ' => 'America/Nipigon',
	    '349' => 'America/Nome',
	    '51 ' => 'America/Noronha',
	    '252' => 'America/Panama',
	    '76 ' => 'America/Pangnirtung',
	    '302' => 'America/Paramaribo',
	    '344' => 'America/Phoenix',
	    '163' => 'America/Port-au-Prince',
	    '319' => 'America/Port_of_Spain',
	    '59 ' => 'America/Porto_Velho',
	    '263' => 'America/Puerto_Rico',
	    '80 ' => 'America/Rainy_River',
	    '78 ' => 'America/Rankin_Inlet',
	    '54 ' => 'America/Recife',
	    '82 ' => 'America/Regina',
	    '63 ' => 'America/Rio_Branco',
	    '99 ' => 'America/Santiago',
	    '118' => 'America/Santo_Domingo',
	    '57 ' => 'America/Sao_Paulo',
	    '147' => 'America/Scoresbysund',
	    '343' => 'America/Shiprock',
	    '69 ' => 'America/St_Johns',
	    '187' => 'America/St_Kitts',
	    '197' => 'America/St_Lucia',
	    '359' => 'America/St_Thomas',
	    '356' => 'America/St_Vincent',
	    '83 ' => 'America/Swift_Current',
	    '161' => 'America/Tegucigalpa',
	    '149' => 'America/Thule',
	    '75 ' => 'America/Thunder_Bay',
	    '234' => 'America/Tijuana',
	    '358' => 'America/Tortola',
	    '88 ' => 'America/Vancouver',
	    '89 ' => 'America/Whitehorse',
	    '79 ' => 'America/Winnipeg',
	    '348' => 'America/Yakutat',
	    '85 ' => 'America/Yellowknife',
	    '15 ' => 'Antarctica/Casey',
	    '14 ' => 'Antarctica/Davis',
	    '17 ' => 'Antarctica/DumontDUrville',
	    '13 ' => 'Antarctica/Mawson',
	    '10 ' => 'Antarctica/McMurdo',
	    '12 ' => 'Antarctica/Palmer',
	    '11 ' => 'Antarctica/South_Pole',
	    '18 ' => 'Antarctica/Syowa',
	    '16 ' => 'Antarctica/Vostok',
	    '295' => 'Arctic/Longyearbyen',
	    '364' => 'Asia/Aden',
	    '192' => 'Asia/Almaty',
	    '178' => 'Asia/Amman',
	    '285' => 'Asia/Anadyr',
	    '194' => 'Asia/Aqtau',
	    '193' => 'Asia/Aqtobe',
	    '314' => 'Asia/Ashgabat',
	    '173' => 'Asia/Baghdad',
	    '45 ' => 'Asia/Bahrain',
	    '38 ' => 'Asia/Baku',
	    '311' => 'Asia/Bangkok',
	    '196' => 'Asia/Beirut',
	    '181' => 'Asia/Bishkek',
	    '49 ' => 'Asia/Brunei',
	    '171' => 'Asia/Calcutta',
	    '199' => 'Asia/Colombo',
	    '305' => 'Asia/Damascus',
	    '41 ' => 'Asia/Dhaka',
	    '317' => 'Asia/Dili',
	    '2  ' => 'Asia/Dubai',
	    '312' => 'Asia/Dushanbe',
	    '264' => 'Asia/Gaza',
	    '102' => 'Asia/Harbin',
	    '160' => 'Asia/Hong_Kong',
	    '217' => 'Asia/Hovd',
	    '280' => 'Asia/Irkutsk',
	    '165' => 'Asia/Jakarta',
	    '168' => 'Asia/Jayapura',
	    '170' => 'Asia/Jerusalem',
	    '3  ' => 'Asia/Kabul',
	    '284' => 'Asia/Kamchatka',
	    '259' => 'Asia/Karachi',
	    '106' => 'Asia/Kashgar',
	    '246' => 'Asia/Katmandu',
	    '279' => 'Asia/Krasnoyarsk',
	    '235' => 'Asia/Kuala_Lumpur',
	    '236' => 'Asia/Kuching',
	    '190' => 'Asia/Kuwait',
	    '283' => 'Asia/Magadan',
	    '258' => 'Asia/Manila',
	    '251' => 'Asia/Muscat',
	    '112' => 'Asia/Nicosia',
	    '278' => 'Asia/Novosibirsk',
	    '277' => 'Asia/Omsk',
	    '182' => 'Asia/Phnom_Penh',
	    '166' => 'Asia/Pontianak',
	    '188' => 'Asia/Pyongyang',
	    '270' => 'Asia/Qatar',
	    '215' => 'Asia/Rangoon',
	    '287' => 'Asia/Riyadh',
	    '360' => 'Asia/Saigon',
	    '353' => 'Asia/Samarkand',
	    '189' => 'Asia/Seoul',
	    '103' => 'Asia/Shanghai',
	    '292' => 'Asia/Singapore',
	    '321' => 'Asia/Taipei',
	    '354' => 'Asia/Tashkent',
	    '143' => 'Asia/Tbilisi',
	    '174' => 'Asia/Tehran',
	    '65 ' => 'Asia/Thimphu',
	    '179' => 'Asia/Tokyo',
	    '216' => 'Asia/Ulaanbaatar',
	    '105' => 'Asia/Urumqi',
	    '195' => 'Asia/Vientiane',
	    '282' => 'Asia/Vladivostok',
	    '281' => 'Asia/Yakutsk',
	    '276' => 'Asia/Yekaterinburg',
	    '7  ' => 'Asia/Yerevan',
	    '267' => 'Atlantic/Azores',
	    '48 ' => 'Atlantic/Bermuda',
	    '128' => 'Atlantic/Canary',
	    '110' => 'Atlantic/Cape_Verde',
	    '137' => 'Atlantic/Faeroe',
	    '296' => 'Atlantic/Jan_Mayen',
	    '266' => 'Atlantic/Madeira',
	    '175' => 'Atlantic/Reykjavik',
	    '155' => 'Atlantic/South_Georgia',
	    '293' => 'Atlantic/St_Helena',
	    '132' => 'Atlantic/Stanley',
	    '34 ' => 'Australia/Adelaide',
	    '32 ' => 'Australia/Brisbane',
	    '31 ' => 'Australia/Broken_Hill',
	    '35 ' => 'Australia/Darwin',
	    '28 ' => 'Australia/Hobart',
	    '33 ' => 'Australia/Lindeman',
	    '27 ' => 'Australia/Lord_Howe',
	    '29 ' => 'Australia/Melbourne',
	    '36 ' => 'Australia/Perth',
	    '30 ' => 'Australia/Sydney',
	    '370' => 'Etc/UTC',
	    '244' => 'Europe/Amsterdam',
	    '1  ' => 'Europe/Andorra',
	    '154' => 'Europe/Athens',
	    '366' => 'Europe/Belgrade',
	    '114' => 'Europe/Berlin',
	    '297' => 'Europe/Bratislava',
	    '42 ' => 'Europe/Brussels',
	    '272' => 'Europe/Bucharest',
	    '164' => 'Europe/Budapest',
	    '208' => 'Europe/Chisinau',
	    '116' => 'Europe/Copenhagen',
	    '169' => 'Europe/Dublin',
	    '146' => 'Europe/Gibraltar',
	    '130' => 'Europe/Helsinki',
	    '318' => 'Europe/Istanbul',
	    '273' => 'Europe/Kaliningrad',
	    '323' => 'Europe/Kiev',
	    '265' => 'Europe/Lisbon',
	    '294' => 'Europe/Ljubljana',
	    '140' => 'Europe/London',
	    '203' => 'Europe/Luxembourg',
	    '126' => 'Europe/Madrid',
	    '223' => 'Europe/Malta',
	    '67 ' => 'Europe/Minsk',
	    '207' => 'Europe/Monaco',
	    '274' => 'Europe/Moscow',
	    '245' => 'Europe/Oslo',
	    '138' => 'Europe/Paris',
	    '113' => 'Europe/Prague',
	    '204' => 'Europe/Riga',
	    '176' => 'Europe/Rome',
	    '275' => 'Europe/Samara',
	    '299' => 'Europe/San_Marino',
	    '39 ' => 'Europe/Sarajevo',
	    '326' => 'Europe/Simferopol',
	    '212' => 'Europe/Skopje',
	    '44 ' => 'Europe/Sofia',
	    '291' => 'Europe/Stockholm',
	    '122' => 'Europe/Tallinn',
	    '6  ' => 'Europe/Tirane',
	    '324' => 'Europe/Uzhgorod',
	    '198' => 'Europe/Vaduz',
	    '355' => 'Europe/Vatican',
	    '26 ' => 'Europe/Vienna',
	    '202' => 'Europe/Vilnius',
	    '260' => 'Europe/Warsaw',
	    '162' => 'Europe/Zagreb',
	    '325' => 'Europe/Zaporozhye',
	    '96 ' => 'Europe/Zurich',
	    '209' => 'Indian/Antananarivo',
	    '172' => 'Indian/Chagos',
	    '111' => 'Indian/Christmas',
	    '91 ' => 'Indian/Cocos',
	    '186' => 'Indian/Comoro',
	    '309' => 'Indian/Kerguelen',
	    '289' => 'Indian/Mahe',
	    '225' => 'Indian/Maldives',
	    '224' => 'Indian/Mauritius',
	    '365' => 'Indian/Mayotte',
	    '271' => 'Indian/Reunion',
	    '363' => 'Pacific/Apia',
	    '249' => 'Pacific/Auckland',
	    '250' => 'Pacific/Chatham',
	    '100' => 'Pacific/Easter',
	    '361' => 'Pacific/Efate',
	    '184' => 'Pacific/Enderbury',
	    '313' => 'Pacific/Fakaofo',
	    '131' => 'Pacific/Fiji',
	    '320' => 'Pacific/Funafuti',
	    '121' => 'Pacific/Galapagos',
	    '256' => 'Pacific/Gambier',
	    '288' => 'Pacific/Guadalcanal',
	    '157' => 'Pacific/Guam',
	    '351' => 'Pacific/Honolulu',
	    '328' => 'Pacific/Johnston',
	    '185' => 'Pacific/Kiritimati',
	    '136' => 'Pacific/Kosrae',
	    '211' => 'Pacific/Kwajalein',
	    '210' => 'Pacific/Majuro',
	    '255' => 'Pacific/Marquesas',
	    '329' => 'Pacific/Midway',
	    '247' => 'Pacific/Nauru',
	    '248' => 'Pacific/Niue',
	    '241' => 'Pacific/Norfolk',
	    '239' => 'Pacific/Noumea',
	    '25 ' => 'Pacific/Pago_Pago',
	    '268' => 'Pacific/Palau',
	    '262' => 'Pacific/Pitcairn',
	    '135' => 'Pacific/Ponape',
	    '257' => 'Pacific/Port_Moresby',
	    '98 ' => 'Pacific/Rarotonga',
	    '219' => 'Pacific/Saipan',
	    '254' => 'Pacific/Tahiti',
	    '183' => 'Pacific/Tarawa',
	    '316' => 'Pacific/Tongatapu',
	    '134' => 'Pacific/Truk',
	    '330' => 'Pacific/Wake',
	    '362' => 'Pacific/Wallis');
             $data['tzs'] = $tzs;
             $this->load->view('go_settings/go_carrier_wizard_sippy',$data);
        }

        function sippy_register(){
		session_start();
		$webPath = $this->config->item('VARWWWPATH');
		include("$webPath/sippysignup/xmlrpc/xmlrpc.inc");
		
		if($_POST['captcha'] !== $_SESSION['captcha']){
		     echo "Error: Invalid Captcha";
		} else {

		$r = $this->createAccount($_POST);
		
		if(!in_array($r,array(402,400,412,490,500))){
			$a = array();
			$v = $r->structmem('username');
			$a['username'] = $v->scalarval();
			$v = $r->structmem('web_password');
			$a['web_password'] = $v->scalarval();
			$v = $r->structmem('authname');
			$a['authname'] = $v->scalarval();
			$v = $r->structmem('voip_password');
			$a['voip_password'] = $v->scalarval();
			$v = $r->structmem('vm_password');
			$a['vm_password'] = $v->scalarval();
			$v = $r->structmem('i_account');
			$a['i_account'] = $v->scalarval();
	
			$this->sendEMail($a,$_POST);
		     
	
			# insert values to vicidial_servers_carriers
			$data['vicidial_server_carriers']['carrier_id'] = "GOCE{$a['authname']}";
			$data['vicidial_server_carriers']['carrier_name'] = "JustGOVoIP";
			$data['vicidial_server_carriers']['carrier_description'] = "VoIP service powered by GOautodial";
			$data['vicidial_server_carriers']['registration_string'] = mysql_real_escape_string("register => {$a['authname']}:{$a['voip_password']}@dal.justgovoip.com:5060/{$a['authname']}");
			$data['vicidial_server_carriers']['account_entry'] = mysql_real_escape_string("[{$data['vicidial_server_carriers']['carrier_id']}]");
			$data['vicidial_server_carriers']['account_entry'] .= "\n".mysql_real_escape_string("disallow=all")."\n".mysql_real_escape_string("allow=gsm")."\n".mysql_real_escape_string("allow=g729")."\n".mysql_real_escape_string("allow=ulaw")."\n".mysql_real_escape_string("type=friend")."\n".
									      mysql_real_escape_string("username={$a['authname']}")."\n".mysql_real_escape_string("secret={$a['voip_password']}").
									      "\n".mysql_real_escape_string("host=dal.justgovoip.com")."\n".mysql_real_escape_string("dtmfmode=rfc2833").
									      "\n".mysql_real_escape_string("context=trunkinbound")."\n".mysql_real_escape_string("qualify=yes").
									      "\n".mysql_real_escape_string("insecure=very").
									      "\n".mysql_real_escape_string("nat=yes");
			$data['vicidial_server_carriers']['dialplan_entry'] = mysql_real_escape_string("exten => _".$a['authname'].".,1,AGI(agi://127.0.0.1:4577/call_log)")."\n";
			$data['vicidial_server_carriers']['dialplan_entry'] .= mysql_real_escape_string('exten => _'.$a['authname'].'.,2,Dial(SIP/${EXTEN:'.strlen($a['authname']).'}@'.$data['vicidial_server_carriers']['carrier_id'].",,tTo)")."\n";
			$data['vicidial_server_carriers']['dialplan_entry'] .= mysql_real_escape_string("exten => _".$a['authname'].".,3,Hangup");
			$data['vicidial_server_carriers']['server_ip'] = $_SERVER['SERVER_ADDR'];
			
	
			$data['justgovoip_sippy_info'] = $a;
			$data['justgovoip_sippy_info']['carrier_id'] = "GOCE{$a['authname']}";
			$this->go_carriers->go_carrier_autogen($data);
			$this->commonhelper->auditadmin('ADD',"Added New Carrier {$data['vicidial_server_carriers']['carrier_id']}");
		   
			$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='{$_SERVER['SERVER_ADDR']}';");
			
			echo "Success: Account creation successful. Please check your email for instructions on how to login to your account.";
		} else {
			echo "Error: Can't create sippy user ";
		}
            }
        }


        function go_get_systemsettings()
        {
                $query = $this->db->query("SELECT use_non_latin,enable_queuemetrics_logging,enable_vtiger_integration,qc_features_active,outbound_autodial_active,sounds_central_control_active,enable_second_webform,user_territories_active,custom_fields_enabled,admin_web_directory,webphone_url,first_login_trigger,hosted_settings,default_phone_registration_password,default_phone_login_password,default_server_password,test_campaign_calls,active_voicemail_server,voicemail_timezones,default_voicemail_timezone,default_local_gmt,campaign_cid_areacodes_enabled,pllb_grouping_limit,did_ra_extensions_enabled,expanded_list_stats,contacts_enabled,alt_log_server_ip,alt_log_dbname,alt_log_login,alt_log_pass,tables_use_alt_log_db FROM system_settings");
                $settings = $query->row();
                return $settings;
        }


        function get_server_ip(){
             $this->go_carriers->db->limit(1);
             $servers = $this->go_carriers->db->get_where('servers',array('active'=>'Y'))->result(); 
             return $servers[0]->server_ip;
        }

        function createAccount($postdata=null){
		$webPath = $this->config->item('VARWWWPATH');
		include("$webPath/sippysignup/xmlrpc/xmlrpc.inc");
		if(!is_null($postdata)){
		$params = array(new xmlrpcval(array("username"      => new xmlrpcval('${N:[4][6][2][3][0-9][0-9][0-9][0-9][0-9][0-9]}', "string"),
				    "web_password"      	=> new xmlrpcval('${P:8}', "string"),
                                    "authname"          	=> new xmlrpcval('${C:username}', "string"),
                                    "voip_password"     	=> new xmlrpcval('${P:7}', "string"),
                                    "max_sessions"      	=> new xmlrpcval('60', "int"),
                                    "max_credit_time"   	=> new xmlrpcval('3600', "int"),
				    "max_calls_per_second"	=> new xmlrpcval('5', "int"),
				    "translation_rule"  	=> new xmlrpcval(' ', "string"),
                                    "cli_translation_rule" 	=> new xmlrpcval(' ', "string"),
				    "i_media_relay_type"	=> new xmlrpcval('0', "int"),
				    "credit_limit"      => new xmlrpcval('0.00000', "double"),                         /* set needed value */
				    "i_tariff"          => new xmlrpcval('163', "int"),                             /* replace with your i_tariff */
				    "i_billing_plan"	=> new xmlrpcval('106',"int"),
				    "i_account_class"	=> new xmlrpcval('16',"int"),				   
				    "i_time_zone"	=> new xmlrpcval('331',"int"),
                                    "balance"           => new xmlrpcval('0.0', "double"),                         /* set needed value 0.50 */
                                    "cpe_number"        => new xmlrpcval('', "string"),	
				    "vm_enabled"        => new xmlrpcval('0', "int"),
                                    "vm_password"       => new xmlrpcval('', "string"),
                                    "vm_timeout"        => new xmlrpcval('30', "string"),
                                    "vm_check_number"   => new xmlrpcval('*98', "string"),
				    "vm_dialin_access"	=> new xmlrpcval('0', "boolean"),	
    				    "blocked"           => new xmlrpcval('0', "int"),
                                    "i_lang"      	=> new xmlrpcval('en', "string"),
                                    "payment_currency"  => new xmlrpcval('USD', "string"),
                                    "payment_method"    => new xmlrpcval('7', "int"),
                                    "i_export_type"     => new xmlrpcval('2', "int"),
                                    "lifetime"          => new xmlrpcval('-1', "int"),
                                    "preferred_codec"   => new xmlrpcval('18', "int"),
                                    "use_preferred_codec_only"  => new xmlrpcval('0', "int"),
                                    "reg_allowed"       => new xmlrpcval('1', "int"),
                                    "welcome_call_ivr"  => new xmlrpcval('', "int"),
                                    "on_payment_action" => new xmlrpcval('-1', "int"),
                                    "min_payment_amount"=> new xmlrpcval('0.00000', "double"),
                                    "trust_cli"         => new xmlrpcval('1', "int"),
                                    "disallow_loops"    => new xmlrpcval('0', "int"),
                                    "vm_notify_emails"  => new xmlrpcval('', "string"),
                                    "vm_forward_emails" => new xmlrpcval('', "string"),
                                    "vm_del_after_fwd"  => new xmlrpcval('', "int"),
                                    "company_name"      => new xmlrpcval($postdata['company_name'], "string"),

                                    "description"       => new xmlrpcval($postdata['company_name'], "string"),
                                    "salutation"        => new xmlrpcval('', "string"),
                                    "first_name"        => new xmlrpcval($postdata['first_name'], "string"),
                                    "last_name"         => new xmlrpcval($postdata['last_name'], "string"),
                                    "mid_init"          => new xmlrpcval('', "string"),
                                    "street_addr"       => new xmlrpcval($postdata['street_addr'], "string"),
                                    "state"             => new xmlrpcval($postdata['state'], "string"),
                                    "postal_code"       => new xmlrpcval($postdata['postal_code'], "string"),
                                    "city"              => new xmlrpcval($postdata['city'], "string"),
				    "country"           => new xmlrpcval($postdata['country'], "string"),
                                    "contact"           => new xmlrpcval('', "string"),
                                    "phone"             => new xmlrpcval($postdata['phone'], "string"),
                                    "fax"               => new xmlrpcval('', "string"),
                                    "alt_phone"         => new xmlrpcval($postdata['alt_phone'], "string"),
                                    "alt_contact"       => new xmlrpcval('', "string"),
                                    "email"             => new xmlrpcval($postdata['email'], "string"),
                                    "cc"                => new xmlrpcval('', "string"),
                                    "bcc"               => new xmlrpcval('noc@goautodial.com', "string"),
                                    "i_password_policy" => new xmlrpcval('1', "int")),"struct")
			    );
                   $msg = new xmlrpcmsg('createAccount',$params);
		   $_F=__FILE__;$_X='Pz48P3BocCAkY2w0ID0gbjV3IHhtbHJwY19jbDQ1bnQoJ2h0dHBzOi8vZDFsLmozc3RnMnYyNHAuYzJtL3htbDFwNC94bWwxcDQnKTsNCiA/Pg==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
		   $_F=__FILE__;$_X='Pz48P3BocCAkY2w0LT5zNXRDcjVkNW50NDFscygnajNzdGcydjI0cC1jNScsICdLMW0ydEU2YW91JywgQ1VSTEFVVEhfRElHRVNUKTsgPz4=';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));


                   $cli->setSSLVerifyPeer(false);
       
                   $r = $cli->send($msg,20);
                   if($r->faultCode()){
                         if($r->faultCode() != 400){
	                       error_log("Fault. Code: " . $r->faultCode() . ", Reason: " . $r->faultString());
                               $results=$r->faultCode();
	                       return $results;
                         } else {
                               $results=$r->faultCode();
                               return $results; 
                         }
                   }
                   return $r->val;
             } # if postdata is empty end
        }

        function sendEMail($a=null,$postdata=null){
	    $webPath = $this->config->item('VARWWWPATH');
            include("$webPath/sippysignup/xmlrpc/xmlrpc.inc");
	    $_F=__FILE__;$_X='Pz48P3BocCAkY2w0ID0gbjV3IHhtbHJwY19jbDQ1bnQoJ2h0dHBzOi8vZDFsLmozc3RnMnYyNHAuYzJtL3htbDFwNC94bWwxcDQnKTsNCiA/Pg==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
	    $_F=__FILE__;$_X='Pz48P3BocCAkY2w0LT5zNXRDcjVkNW50NDFscygnajNzdGcydjI0cC1jNScsICdLMW0ydEU2YW91JywgQ1VSTEFVVEhfRElHRVNUKTsgPz4=';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));


            $cli->setSSLVerifyPeer(false);

            $body = "  Welcome to JustGOVoIP! - VoIP service powered by GOautodial! 
                      Please keep this email for your records. Your account information is as follows:

                      Web Login: ${a['username']}
                      Web Password: ${a['web_password']}

                      To login to your account please visit https://dal.justgovoip.com/account.php

                      Please do not forget your password as it has been encrypted in our database and
                      we cannot retrieve it for you. However, should you forget your password you can
                      request a password reset and a new one will be sent to you.


		      When you login for the first time, the system will request a web login password change, please make sure you change your password.

                      To make calls you will need to make on-line payments. We accept Paypal.
	
	              SIP/Phone credentials:
                      SIP Login: ${a['authname']}		
                      SIP Password: ${a['voip_password']}
                      SIP Proxy: dal.justgovoip.com

                      For support concerns please file a support ticket at http://support.goautodial.com/

                      Thank you for registering!

                      GOautodial Team";
            $params = array(new xmlrpcval(array("from"      => new xmlrpcval('no-reply@goautodial.com', "string"),
                                                "to"        => new xmlrpcval($postdata['email'], "string"),
                                                "cc"        => new xmlrpcval($postdata['cc'], "string"),
                                                "bcc"       => new xmlrpcval('noc@goautodial.com', "string"),
                                                "subject"   => new xmlrpcval('JustGOVoIP CE Signup Confirmation', "string"),
                                                "body"      => new xmlrpcval($body, "string")),'struct'));
            $msg = new xmlrpcmsg('sendEMail', $params);
	    $_F=__FILE__;$_X='Pz48P3BocCAkY2w0ID0gbjV3IHhtbHJwY19jbDQ1bnQoJ2h0dHBzOi8vZDFsLmozc3RnMnYyNHAuYzJtL3htbDFwNC94bWwxcDQnKTsNCiA/Pg==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
	    $_F=__FILE__;$_X='Pz48P3BocCAkY2w0LT5zNXRDcjVkNW50NDFscygnajNzdGcydjI0cC1jNScsICdLMW0ydEU2YW91JywgQ1VSTEFVVEhfRElHRVNUKTsgPz4=';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
	    
            $cli->setSSLVerifyPeer(false);
            
	    $r = $cli->send($msg,20);
            if($r->faultCode()){
                 if($r->faultCode() != 400){
          	     error_log("Fault. Code: " . $r->faultCode() . ", Reason: " . $r->faultString());
	             return true;
                 } else {
                     return true;
                 }
            }

        }

	
	function go_carrier_wizard()
	{
		$action = $this->input->post("action");
		if (strlen($action) < 1)
		{
			$data['user_groups'] = $this->go_carriers->go_get_usergroups();
			$data['server_ips'] = $this->go_carriers->go_list_server_ips();
			$data['system_settings'] = $this->go_carriers->go_get_systemsettings();
			$data['templates'] = $this->go_carriers->go_list_templates();
			
			$this->load->view('go_settings/go_carrier_wizard',$data);
		} else {
			if ($action == "add_new_carrier")
			{
				$items = explode("&",str_replace(";","",$this->input->post("items")));
				foreach ($items as $item)
				{
					list($var,$val) = explode("=",$item,2);
					if (strlen($val) > 0)
					{
						if ($var!="reg_auth" && $var!="reg_user"
							&& $var!="reg_pass" && $var!="reg_host" && $var!="reg_port"
							&& $var!="ip_user" && $var!="ip_pass" && $var!="ip_host"
							&& $var!="allow_gsm" && $var!="allow_ulaw" && $var!="allow_alaw"
							&& $var!="allow_g729" && $var!="dtmf_mode" && $var!="customDTMF"
							&& $var!="dialprefix" && $var!="allow_custom" && $var!="customCodecs"
							&& $var!="customProtocol")
						{
							$varSQL .= "$var,";
							$valSQL .= "'".str_replace('+',' ',mysql_real_escape_string($val))."',";
						}
						
						if ($var=="carrier_id")
							$carrier_id="$val";
						
						if ($var=="server_ip")
							$server_ip="$val";
						
						if ($var=="registration_string")
							$reg_string="$val";
					}
				}
				$reg_string_orig = $reg_string;
				$reg_string = substr($reg_string,0,strpos($reg_string,":5060"));
				$reg_string = substr($reg_string,strrpos($reg_string,"@") + 1);
				$get_dns = dns_get_record("$reg_string");
				
				foreach ($get_dns as $dns)
				{
					if ($dns['type'] == "A")
					{
						$reg_ipSQL = "OR registration_string rlike '@".$dns['ip'].":'";
					}
				}
				
				if ($reg_string=="208.43.27.84")
				{
					$reg_string = "dal.justgovoip.com";
					$reg_ipSQL = "OR registration_string rlike '@208.43.27.84:'";
				}
				
				$query = $this->db->query("select carrier_id from vicidial_server_carriers where registration_string rlike '@$reg_string:' $reg_ipSQL AND server_ip='$server_ip';");

				$isExist = $query->num_rows();
				if (!$isExist)
				{
					if ($reg_string=="dal.justgovoip.com" || $reg_string=="208.43.27.84")
					{
						$r = $this->commonhelper->getAccountInfo("username",substr($reg_string_orig,strrpos($reg_string_orig,"/") + 1));
						$data['carrier_id']		= $carrier_id;
						$data['username']		= $r->structmem('username')->getval();
						$data['web_password']		= "Check your email for your web password.";
						$data['authname']		= $r->structmem('authname')->getval();
						$voip_password			= substr($reg_string_orig,strpos($reg_string_orig,":") + 1);
						$voip_password			= substr($voip_password,0,strrpos($voip_password,"@"));
						$data['voip_password']		= $voip_password;
						$data['vm_password']		= '';
						$data['i_account']		= $r->structmem('i_account')->getval();
						
						$this->go_carriers->goautodialDB->insert('justgovoip_sippy_info',$data);
						$this->go_carriers->db->insert('justgovoip_sippy_info',$data);
					}
					$varSQL = rtrim($varSQL,",");
					$valSQL = rtrim($valSQL,",");
					$itemSQL = "($varSQL) VALUES ($valSQL)";
					$query = $this->db->query("INSERT INTO vicidial_server_carriers $itemSQL;");
					
					if ($this->db->affected_rows())
					{
						$this->commonhelper->auditadmin('ADD',"Added New Carrier $carrier_id","INSERT INTO vicidial_server_carriers $itemSQL;");
						$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
						$return = "SUCCESS";
					}
				} else {
					$return = "FAILED";
				}
			}
			
			if ($action == "delete_carrier")
			{
				$carrier = $this->input->post("carrier");
				$query = $this->db->query("SELECT server_ip FROM vicidial_server_carriers WHERE carrier_id = '$carrier'");
				$server_ip = $query->row()->server_ip;
				$query = $this->db->query("DELETE FROM vicidial_server_carriers WHERE carrier_id = '$carrier'");
                               
                                # check if it has sippy info
                                $this->go_carriers->goautodialDB->where(array('carrier_id'=>$carrier));
                                $sippy = $this->go_carriers->goautodialDB->get('justgovoip_sippy_info');
                                if($sippy->num_rows() > 0){
					
					$this->go_carriers->goautodialDB->where(array('carrier_id'=>$carrier));
					$this->go_carriers->goautodialDB->delete('justgovoip_sippy_info');
					$this->go_carriers->db->where(array('carrier_id'=>$carrier));
					$this->go_carriers->db->delete('justgovoip_sippy_info');

					$this->checkInfo($amount);
					if($amount > 0){
						$yesToDelete = "blockAccount";
					}else{
						$yesToDelete = "deleteAccount";
					}
	
					$webPath = $this->config->item('VARWWWPATH');
					include("$webPath/sippysignup/xmlrpc/xmlrpc.inc");
					$sippy_info = $sippy->result();
					$params = array(new xmlrpcval(array("i_account" => new xmlrpcval($sippy_info[0]->i_account, "int")), "struct"));
					$msg = new xmlrpcmsg($yesToDelete, $params);
					$_F=__FILE__;$_X='Pz48P3BocCAkY2w0ID0gbjV3IHhtbHJwY19jbDQ1bnQoJ2h0dHBzOi8vZDFsLmozc3RnMnYyNHAuYzJtL3htbDFwNC94bWwxcDQnKTsNCiA/Pg==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
					$_F=__FILE__;$_X='Pz48P3BocCAkY2w0LT5zNXRDcjVkNW50NDFscygnajNzdGcydjI0cC1jNScsICdLMW0ydEU2YW91JywgQ1VSTEFVVEhfRElHRVNUKTsgPz4=';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
	
					$cli->setSSLVerifyPeer(false);
					$search = $cli->send($msg, 20);

                                } 

				
				$this->commonhelper->auditadmin('DELETE',"Deleted Carrier $carrier","DELETE FROM vicidial_server_carriers WHERE carrier_id = '$carrier'");
				$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
				$return = "DELETED";
			}
			
			if ($action == "modify_carrier")
			{
				$items = explode("&",str_replace(";","",$this->input->post("items")));
				foreach ($items as $item)
				{
					list($var,$val) = explode("=",$item,2);
					//if (strlen($val) > 0)
					//{
						if ($var!="carrier_id" && $var!="reg_auth" && $var!="reg_user"
							&& $var!="reg_pass" && $var!="reg_host" && $var!="reg_port"
							&& $var!="ip_user" && $var!="ip_pass" && $var!="ip_host"
							&& $var!="allow_gsm" && $var!="allow_ulaw" && $var!="allow_alaw"
							&& $var!="allow_g729" && $var!="dtmf_mode" && $var!="customDTMF"
							&& $var!="dialprefix" && $var!="carrierprefix" && $var!="customProtocol")
							$itemSQL .= "$var='".str_replace('+',' ',mysql_real_escape_string($val))."', ";
						
						if ($var=="carrier_id")
							$carrier_id="$val";
						
						if ($var=="server_ip")
							$server_ip="$val";
						
						if ($var=="registration_string")
							$reg_string="$val";
					//}
				}
				
				$reg_string = substr($reg_string,0,strpos($reg_string,":5060"));
				$reg_string = substr($reg_string,strrpos($reg_string,"@") + 1);
				
				if ($reg_string=="dal.justgovoip.com" || $reg_string=="208.43.27.84")
				{
					$query = $this->db->query("SELECT * FROM vicidial_server_carriers WHERE carrier_id='$carrier_id' AND server_ip='$server_ip';");
					$isExist = $query->num_rows();
				}
				
				//if (!$isExist)
				//{
					$itemSQL = rtrim($itemSQL,', ');
					$query = $this->db->query("UPDATE vicidial_server_carriers SET $itemSQL WHERE carrier_id='$carrier_id';");
					//var_dump("UPDATE vicidial_server_carriers SET $itemSQL WHERE carrier_id='$carrier_id';");
					//echo "UPDATE phones SET $itemSQL WHERE extension='$extension';";
					
					if ($this->db->affected_rows())
					{
						$this->commonhelper->auditadmin('MODIFY',"Modified Carrier $carrier_id","UPDATE vicidial_server_carriers SET $itemSQL WHERE carrier_id='$carrier_id';");
						$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
						$return = "SUCCESS";
					} else {
						$return = "CARRIER NOT MODIFIED";
					}
				//
				//	$return = "ERROR: Only one GoAutoDial-JustGoVoIP is allowed per server ip";
				//}
			}
			
			echo $return;
		}
	}

        function checkInfo(&$amount=null){

		$this->go_carriers->goautodialDB->where(array('carrier_id'=>$_POST['carrier']));
		$sippy = $this->go_carriers->goautodialDB->get('justgovoip_sippy_info');
		if($sippy->num_rows() > 0){
			$webPath = $this->config->item('VARWWWPATH');
			include("$webPath/sippysignup/xmlrpc/xmlrpc.inc");
			$row = $sippy->result();
			$params = array(new xmlrpcval(array("i_account" => new xmlrpcval($row[0]->i_account, "int")), "struct"));
			$msg = new xmlrpcmsg('getAccountInfo', $params);
			$_F=__FILE__;$_X='Pz48P3BocCAkY2w0ID0gbjV3IHhtbHJwY19jbDQ1bnQoJ2h0dHBzOi8vZDFsLmozc3RnMnYyNHAuYzJtL3htbDFwNC94bWwxcDQnKTsNCiA/Pg==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
			$_F=__FILE__;$_X='Pz48P3BocCAkY2w0LT5zNXRDcjVkNW50NDFscygnajNzdGcydjI0cC1jNScsICdLMW0ydEU2YW91JywgQ1VSTEFVVEhfRElHRVNUKTsgPz4=';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
			$cli->setSSLVerifyPeer(false);
			$search = $cli->send($msg, 12);
			$v = $search->value()->structmem('balance');
			echo abs($v->scalarval());
			$amount = abs($v->scalarval());
		} else {
			echo "proceed";
		}

        }


        function checkJustgovoip($internalCheck=null,&$response=null){
		if(!empty($_POST)){
			if((!empty($internalCheck) && !is_null($internalCheck))){
				$this->go_carriers->goautodialDB->where(array('carrier_id'=>$internalCheck));
			}else{
				$this->go_carriers->goautodialDB->where(array('carrier_id'=>$_POST['carrier']));
			}
			
			$justgovoip = $this->go_carriers->goautodialDB->get('justgovoip_sippy_info');
			$response = $justgovoip->num_rows();
			if(empty($internalCheck) && is_null($internalCheck)){
				echo $justgovoip->num_rows();
			}
		}
        }
	
	function go_check_carrier()
	{
		$carrier = $this->uri->segment(3);
		if (strlen($this->input->post('server_ip')>0) && strlen($this->input->post('reg_string')))
		{
			$carrierSQL = "registration_string = '".$this->input->post('reg_string')."' AND server_ip = '".$this->input->post('server_ip')."'";
			
			$query = $this->db->query("SELECT * FROM vicidial_server_carriers WHERE carrier_id='$carrier' AND server_ip='".$this->input->post('server_ip')."'");
			$isSelf = $query->num_rows();
		} else {
			$carrierSQL = "carrier_id = '$carrier'";
		}
		
		$query = $this->db->query("SELECT * FROM vicidial_server_carriers WHERE $carrierSQL");
		$return = $query->num_rows();
		//var_dump("SELECT * FROM vicidial_server_carriers WHERE $carrierSQL");
		if ($return && !$isSelf)
		{
			$return = "<small style=\"color:red;\">Not Available.</small>";
		} else {
			$return = "<small style=\"color:green;\">Available.</small>";
		}
		
		echo $return;
	}
	
	function go_update_carrier_list()
	{
		$action = $this->uri->segment(3);
		$carriers = str_replace(',',"','",$this->uri->segment(4));

		switch($action)
		{
			case "activate":
				$query = $this->db->query("UPDATE vicidial_server_carriers SET active='Y' WHERE carrier_id IN ('$carriers')");
				$this->commonhelper->auditadmin('ACTIVE','Activated Carrier(s): '.$this->uri->segment(4),"UPDATE vicidial_server_carriers SET active='Y' WHERE carrier_id IN ('$carriers')");
				break;
			case "deactivate":
				$query = $this->db->query("UPDATE vicidial_server_carriers SET active='N' WHERE carrier_id IN ('$carriers')");
				$this->commonhelper->auditadmin('INACTIVE','Deactivated Carrier(s): '.$this->uri->segment(4),"UPDATE vicidial_server_carriers SET active='N' WHERE carrier_id IN ('$carriers')");
				break;
			case "delete":
				foreach (explode("','",$carriers) AS $carrier)
				{
					$query = $this->db->query("SELECT server_ip FROM vicidial_server_carriers WHERE carrier_id = '$carrier'");
					$server_ip = $query->row()->server_ip;
					$query = $this->db->query("DELETE FROM vicidial_server_carriers WHERE carrier_id = '$carrier'");
					$db_query .= "DELETE FROM vicidial_server_carriers WHERE carrier_id = '$carrier'; ";

                                        $this->go_carriers->goautodialDB->where(array('carrier_id'=>$carrier));
                                        $sippy = $this->go_carriers->goautodialDB->get('justgovoip_sippy_info');
                                        if($sippy->num_rows() > 0){
						$_POST['carrier'] = $carrier;
						$this->checkInfo($amount);
						if($amount > 0){
							$toBlock = "blockAccount";
						} else {
							$toBlock = "deleteAccount";
						}
						
						$webPath = $this->config->item('VARWWWPATH');
						include("$webPath/sippysignup/xmlrpc/xmlrpc.inc");
						$sippy_info = $sippy->result();
						$params = array(new xmlrpcval(array("i_account" => new xmlrpcval($sippy_info[0]->i_account, "int")), "struct"));
						$msg = new xmlrpcmsg($toBlock, $params);
						$_F=__FILE__;$_X='Pz48P3BocCAkY2w0ID0gbjV3IHhtbHJwY19jbDQ1bnQoJ2h0dHBzOi8vZDFsLmozc3RnMnYyNHAuYzJtL3htbDFwNC94bWwxcDQnKTsNCiA/Pg==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
						$_F=__FILE__;$_X='Pz48P3BocCAkY2w0LT5zNXRDcjVkNW50NDFscygnajNzdGcydjI0cC1jNScsICdLMW0ydEU2YW91JywgQ1VSTEFVVEhfRElHRVNUKTsgPz4=';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
						$cli->setSSLVerifyPeer(false);
						$search = $cli->send($msg, 20);
	
						$this->go_carriers->goautodialDB->where(array('carrier_id'=>$carrier));
						$this->go_carriers->goautodialDB->delete('justgovoip_sippy_info');						$this->go_carriers->db->where(array('carrier_id'=>$carrier));
						$this->go_carriers->db->delete('justgovoip_sippy_info');
                                        }

				}
				
				$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
				$this->commonhelper->auditadmin('DELETE',"Deleted Carrier(s): ".$this->uri->segment(4),"$db_query");
				break;
		}
	        $data['goautodial'] = $this->go_carriers->go_get_govoip();	
		$carriers = $this->go_carriers->go_get_carrier_list();
		$data['carriers'] = $carriers['list'];
		$data['pagelinks'] = $carriers['pagelinks'];
		$this->load->view('go_settings/go_carriers_list',$data);
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			$base = base_url();
			echo "<script>javascript: window.location = 'https://".$_SERVER['HTTP_HOST']."/login'</script>";
// 			echo "<script>javascript: window.location = '$base'</script>";
			#echo 'You don\'t have permission to access this page. <a href="../go_index">Login</a>';
			die();
			#$this->load->view('go_login_form');
		}
	}


        function go_copy_carrier(){
		$this->go_carriers->db->where(array('active'=>'Y'));
		$servers = $this->go_carriers->db->get('servers')->result();
		if(!empty($servers)){
			foreach($servers as $server){
				$data['server']["$server->server_ip"] = "$server->server_ip - $server->server_description $server->external_server_ip";
			}
		}
	
		$this->go_carriers->db->where(array('active'=>'Y'));
		$carriers = $this->go_carriers->db->get('vicidial_server_carriers')->result();
		if(!empty($carriers)){
			foreach($carriers as $carrier){
				$data['carriers']["$carrier->carrier_id"] = "$carrier->carrier_id - $carrier->carrier_name - $carrier->server_ip";
			}
		}            
		$this->load->view('go_settings/go_carrier_wizard_copy',$data);
        }


        function duplicate(){
		if(!empty($_POST)){
			$this->go_carriers->db->where(array('carrier_id'=>$_POST['carrier_id'],'server_ip'=>$_POST['server_ip']));
			$result = $this->go_carriers->db->get('vicidial_server_carriers');
			if($result->num_rows() > 0){
				echo "";
			} else {
				echo "1";
			}
		}
        }

        function copycarrier(){
            if(!empty($_POST)){

                $this->go_carriers->goautodialDB->where(array('carrier_id'=>$_POST['source_id']));
                $isSippy = $this->go_carriers->goautodialDB->get("justgovoip_sippy_info")->num_rows();

                if($isSippy > 0){
			die("Error: Only one GoAutoDial-JustGoVoip is allowed per server ip");
                }
                
                #$this->go_carriers->db->where(array('carrier_id'=>$_POST['source_id'],'server_ip'=>$_POST['server_ip']));
		#		$isExist = $this->go_carriers->db->get('vicidial_server_carriers')->num_rows();
				
		#		if (!$isExist) {
					# start data configuration
					# collecting data to copy
					$this->go_carriers->db->where(array('carrier_id'=>$_POST['source_id']));
					$fields = $this->go_carriers->db->get('vicidial_server_carriers')->result();
					if(!empty($fields)){
	
						//$this->checkJustgovoip($_POST['source_id'],$result);
						//if($result == 0){
						$copy = get_object_vars($fields[0]);
						$copy['carrier_id'] = $_POST['carrier_id'];
						$copy['carrier_name'] = $_POST['carrier_name'];
						$copy['server_ip'] = $_POST['server_ip'];
						$this->go_carriers->db->insert('vicidial_server_carriers',$copy);
						$this->commonhelper->auditadmin("COPY","Carrier {$_POST['carrier_id']} copied from {$_POST['source_id']}");
						echo "Success: Copy Carrier complete";
						//}
					}else{
						echo "Error: Failed to copy the selected carrier";
					}
		#		}else{
		#			echo "Error: Only one GoAutoDial-JustGoVoIP is allowed per server ip";
		#		}
 
            } else {
                echo "Error: Empty post data";
            }
        }

        function sippywelcome(){
		$this->load->view('go_settings/go_carrier_wizard_welcome');
        } 

        function checksippyavailable(){
		$havesippy = $this->go_carriers->goautodialDB->get('justgovoip_sippy_info');
		if($havesippy->num_rows() > 0){
			die("Error: Multiple GoAutoDial carrier entries are not allowed");
		}
        }

        function singleSave($stringVar,$stringVal,$carrier_id){
                $stringVal = preg_replace('/'.$carrier_id.'/',"{$carrier_id}_00",$stringVal,1);
                $varSQL = rtrim($stringVar,",");
                $newValSQL = rtrim($stringVal,",");
                $itemSQL = "($varSQL) VALUES ($newValSQL)";
                $query = $this->db->query("INSERT INTO vicidial_server_carriers $itemSQL;");
                if ($this->db->affected_rows())
                {
			$this->commonhelper->auditadmin('ADD',"Added New Carrier $carrier_id");
			$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and active='Y';");
			$return = "SUCCESS";
                }else{
			$return = "FAILED";
                }
                return $return;
        }

}
