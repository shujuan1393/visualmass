<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once '../config/db.php';

$selectSql = "SELECT value from settings WHERE type='general'";
$savedresult = mysqli_query($link, $selectSql);

if (!mysqli_query($link,$selectSql)) {
    echo("Error description: " . mysqli_error($link));
} else {
    $savedrow = mysqli_fetch_assoc($savedresult);
    $valArr = explode("&", $savedrow['value']);
?>
<html>    
    <div id="framecontent">
        <div class='innertube'>
        <?php
            require '../nav/adminSidebar.php';
        ?>
        </div>
    </div>
    <div id="maincontent">
        <div class="innertube">
        <h2>Settings - General</h2>
        
        <form id='generalSettings' action='saveGeneralSettings.php' method='post'>
            Primary Store: 
            <?php
                $locSql = "Select * from locations";
                
                $result = mysqli_query($link, $locSql);
                
                if (!mysqli_query($link,$locSql))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows === 0) {
                    echo "You have not created any locations yet.<br>";
                    echo "Create a location <a href='locations.php'>here</a>";
                } else {
                    $priStore = explode("primary=", $valArr[0]);
                    
                    echo "<select name='primary' id='primary'>";
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['code']."'";
                        if (strcmp($priStore[1], $row['code'])) {
                            echo " selected";
                        }
                        echo ">".$row['name']."</option>";
                   } 
                   echo "</select><br>";
                }
            }
            ?>
            <p class='setting-tooltips'>*Set Visual Mass's primary location</p>
            <?php
            $email = explode("email=", $valArr[1]);
            ?>
            Contact Email: <input type='text' name='email' id='email' 
                                  value='<?php if (!empty($email[1])) echo $email[1]; ?>'><br>
            <p class='setting-tooltips'>*Set the default contact email address for Visual Mass</p>
            
            Currency: 
            <?php 
                $currency_list = array (
                    'ALL' => 'Albania Lek',
                    'AFN' => 'Afghanistan Afghani',
                    'ARS' => 'Argentina Peso',
                    'AWG' => 'Aruba Guilder',
                    'AUD' => 'Australia Dollar',
                    'AZN' => 'Azerbaijan New Manat',
                    'BSD' => 'Bahamas Dollar',
                    'BBD' => 'Barbados Dollar',
                    'BDT' => 'Bangladeshi taka',
                    'BYR' => 'Belarus Ruble',
                    'BZD' => 'Belize Dollar',
                    'BMD' => 'Bermuda Dollar',
                    'BOB' => 'Bolivia Boliviano',
                    'BAM' => 'Bosnia and Herzegovina Convertible Marka',
                    'BWP' => 'Botswana Pula',
                    'BGN' => 'Bulgaria Lev',
                    'BRL' => 'Brazil Real',
                    'BND' => 'Brunei Darussalam Dollar',
                    'KHR' => 'Cambodia Riel',
                    'CAD' => 'Canada Dollar',
                    'KYD' => 'Cayman Islands Dollar',
                    'CLP' => 'Chile Peso',
                    'CNY' => 'China Yuan Renminbi',
                    'COP' => 'Colombia Peso',
                    'CRC' => 'Costa Rica Colon',
                    'HRK' => 'Croatia Kuna',
                    'CUP' => 'Cuba Peso',
                    'CZK' => 'Czech Republic Koruna',
                    'DKK' => 'Denmark Krone',
                    'DOP' => 'Dominican Republic Peso',
                    'XCD' => 'East Caribbean Dollar',
                    'EGP' => 'Egypt Pound',
                    'SVC' => 'El Salvador Colon',
                    'EEK' => 'Estonia Kroon',
                    'EUR' => 'Euro Member Countries',
                    'FKP' => 'Falkland Islands (Malvinas) Pound',
                    'FJD' => 'Fiji Dollar',
                    'GHC' => 'Ghana Cedis',
                    'GIP' => 'Gibraltar Pound',
                    'GTQ' => 'Guatemala Quetzal',
                    'GGP' => 'Guernsey Pound',
                    'GYD' => 'Guyana Dollar',
                    'HNL' => 'Honduras Lempira',
                    'HKD' => 'Hong Kong Dollar',
                    'HUF' => 'Hungary Forint',
                    'ISK' => 'Iceland Krona',
                    'INR' => 'India Rupee',
                    'IDR' => 'Indonesia Rupiah',
                    'IRR' => 'Iran Rial',
                    'IMP' => 'Isle of Man Pound',
                    'ILS' => 'Israel Shekel',
                    'JMD' => 'Jamaica Dollar',
                    'JPY' => 'Japan Yen',
                    'JEP' => 'Jersey Pound',
                    'KZT' => 'Kazakhstan Tenge',
                    'KPW' => 'Korea (North) Won',
                    'KRW' => 'Korea (South) Won',
                    'KGS' => 'Kyrgyzstan Som',
                    'LAK' => 'Laos Kip',
                    'LVL' => 'Latvia Lat',
                    'LBP' => 'Lebanon Pound',
                    'LRD' => 'Liberia Dollar',
                    'LTL' => 'Lithuania Litas',
                    'MKD' => 'Macedonia Denar',
                    'MYR' => 'Malaysia Ringgit',
                    'MUR' => 'Mauritius Rupee',
                    'MXN' => 'Mexico Peso',
                    'MNT' => 'Mongolia Tughrik',
                    'MZN' => 'Mozambique Metical',
                    'NAD' => 'Namibia Dollar',
                    'NPR' => 'Nepal Rupee',
                    'ANG' => 'Netherlands Antilles Guilder',
                    'NZD' => 'New Zealand Dollar',
                    'NIO' => 'Nicaragua Cordoba',
                    'NGN' => 'Nigeria Naira',
                    'NOK' => 'Norway Krone',
                    'OMR' => 'Oman Rial',
                    'PKR' => 'Pakistan Rupee',
                    'PAB' => 'Panama Balboa',
                    'PYG' => 'Paraguay Guarani',
                    'PEN' => 'Peru Nuevo Sol',
                    'PHP' => 'Philippines Peso',
                    'PLN' => 'Poland Zloty',
                    'QAR' => 'Qatar Riyal',
                    'RON' => 'Romania New Leu',
                    'RUB' => 'Russia Ruble',
                    'SHP' => 'Saint Helena Pound',
                    'SAR' => 'Saudi Arabia Riyal',
                    'RSD' => 'Serbia Dinar',
                    'SCR' => 'Seychelles Rupee',
                    'SGD' => 'Singapore Dollar',
                    'SBD' => 'Solomon Islands Dollar',
                    'SOS' => 'Somalia Shilling',
                    'ZAR' => 'South Africa Rand',
                    'LKR' => 'Sri Lanka Rupee',
                    'SEK' => 'Sweden Krona',
                    'CHF' => 'Switzerland Franc',
                    'SRD' => 'Suriname Dollar',
                    'SYP' => 'Syria Pound',
                    'TWD' => 'Taiwan New Dollar',
                    'THB' => 'Thailand Baht',
                    'TTD' => 'Trinidad and Tobago Dollar',
                    'TRY' => 'Turkey Lira',
                    'TRL' => 'Turkey Lira',
                    'TVD' => 'Tuvalu Dollar',
                    'UAH' => 'Ukraine Hryvna',
                    'GBP' => 'United Kingdom Pound',
                    'UGX' => 'Uganda Shilling',
                    'USD' => 'United States Dollar',
                    'UYU' => 'Uruguay Peso',
                    'UZS' => 'Uzbekistan Som',
                    'VEF' => 'Venezuela Bolivar',
                    'VND' => 'Viet Nam Dong',
                    'YER' => 'Yemen Rial',
                    'ZWD' => 'Zimbabwe Dollar'
                );
                
                $currency = explode("curr=", $valArr[2]);
                echo "<select name='currency'>";
                foreach($currency_list as $key => $value) {
                    $cur = $value ."(".$key.")";
                    echo "<option value='".$key."' ";
                    if (strcmp($key, $currency[1]) === 0) {
                        echo "selected";
                    }
                    echo ">".$cur."</option>";
                }
                echo "</select><br>";
            ?>
            
            <p class='setting-tooltips'>*Set default currency to be used throughout Visual Mass</p>
            
            Timezone: 
            <?php 
                static $regions = array(
                    DateTimeZone::AFRICA,
                    DateTimeZone::AMERICA,
                    DateTimeZone::ANTARCTICA,
                    DateTimeZone::ASIA,
                    DateTimeZone::ATLANTIC,
                    DateTimeZone::AUSTRALIA,
                    DateTimeZone::EUROPE,
                    DateTimeZone::INDIAN,
                    DateTimeZone::PACIFIC,
                );

                $timezones = array();
                foreach( $regions as $region )
                {
                    $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
                }

                $timezone_offsets = array();
                foreach( $timezones as $timezone )
                {
                    $tz = new DateTimeZone($timezone);
                    $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
                }

                // sort timezone by offset
                asort($timezone_offsets);

                $timezone_list = array();
                foreach( $timezone_offsets as $timezone => $offset )
                {
                    $offset_prefix = $offset < 0 ? '-' : '+';
                    $offset_formatted = gmdate( 'H:i', abs($offset) );

                    $pretty_offset = "UTC${offset_prefix}${offset_formatted}";

                    $timezone_list[$timezone] = "(${pretty_offset}) $timezone";
                }
                
                
                $time = explode("timezone=", $valArr[3]);
                
                echo "<select name='timezone'>";
                foreach($timezone_list as $key => $timevalue) {
                    echo "<option value='".$timevalue."' ";
                    
                    if (strcmp($timevalue, $time[1])===0) {
                        echo "selected";
                    }
                    echo ">".$timevalue."</option>";
                }
                echo "</select><br>";
            ?>
            <p class='setting-tooltips'>*Set default timezone to be used throughout Visual Mass</p>
            <input type='submit' name='submit' value='Save Changes' />
        </form>
        <div id="genSetError" style='color:red'>
            <?php
                if (isset($_SESSION['gensetError'])) {
                    echo $_SESSION['gensetError'];
                }
            ?>
        </div>
        <div id="genSetSuccess" style='color:green'>
            <?php
                if (isset($_SESSION['updateGenSetSuccess'])) {
                    echo $_SESSION['updateGenSetSuccess'];
                }
            ?>
        </div>
        </div>
    </div>
</html>
<?php } ?>