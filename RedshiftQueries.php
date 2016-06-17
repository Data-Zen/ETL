<?php
$debug=1;
date_default_timezone_set('UTC');//or change to whatever timezone you want
echo "\n\n*******Running RedshiftQueries.php*************\n\n";

include 'credentials/PBBCredentials.php';
$connect = pg_connect($PBBModifyCredentials);

$sql="delete contact_staging;";

if ($debug==1)
{
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
}
$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
echo "Rows affected $rowsaffected \n\n";


$sql="copy contact_staging from 's3://pbb-redshift/results.json' CREDENTIALS 'aws_access_key_id=AKIAJRGCCOIYLYRYFGEQ;aws_secret_access_key=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' json 'auto' ;";

if ($debug==1)
{
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
}
$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
echo "Rows affected $rowsaffected \n\n";



$sql="delete from contact where id in(select id from contact_staging);";

if ($debug==1)
{
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
}
$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
echo "Rows affected $rowsaffected \n\n";





$sql="
insert into contact
select id::int 
       , record_manager
       , address_1
       , address_2
       , city
       , state
       , zip
       , id_status
       , primary_phone
       , fax
       , work_phone
       , mobile_phone
       , last_results
       , referred_by
       , breed
       , gender
       , customers_ip
       , shipping_costs
       , total_cost
       , billing_address_1
       , billing_city
       , billing_state
       , billing_zip
       , additional_phone_number
       , kennel_name
       , breeder_name
       , breeder_phone
       , rabies_given
       , case when puppy_birthdate <> '0000-00-00' then puppy_birthdate::date else null end	   
       , case when rabies_date <> '0000-00-00' then rabies_date::date else null end	   
	   , first_name
       , last_name
       , relationship_to_buyer
       , purchased_nuvet
       , case when lead_date <> '0000-00-00' then lead_date::date else null end	   
       , payment_method
       , puppy_id
       , case when date_sold <> '0000-00-00' then date_sold::timestamp else null end	   
       , deposit
       , puppy_price
	   , case when preferred_flight_date <> '0000-00-00' then preferred_flight_date::date else null end
       , airport_choice_1
       , airport_choice_2
       , additional_specifications
       , airport
	   , case when actual_flight_date <> '0000-00-00' then actual_flight_date::date else null end	   
       , flight_air_waybill
       , sms_opt_in
       , flight_confirmation
       , financing_status
       , financing_approved_amount
       , financing_changed_mind_reason
       , pup_package::int
       , pup_package_cost
       , airline
       , airline_flight
       , arrival_time
       , breeder_payment
       , breeder
       , additional_specs
       , puppy_url
       , lead_time
       , cargo_local_phone
       , airline_phone
       , flight_notes
       , breeder_email
       , b_breed
       , b_gender
       , b_puppy_id::int
       , first_vac
	   , case when first_vac_date <> '0000-00-00' then first_vac_date::date else null end	   	   
       , second_vac
	   , case when second_vac_date <> '0000-00-00' then second_vac_date::date else null end	   	   
       , third_vac
	   , case when third_vac_date <> '0000-00-00' then third_vac_date::date else null end	   	   
	   , first_dewormer
	   , case when first_wormer_date <> '0000-00-00' then first_wormer_date::date else null end	   	   
       , second_dewormer
	   , case when second_wormer_date <> '0000-00-00' then second_wormer_date::date else null end	   	   
       , third_dewormer
	   , case when third_wormer_date <> '0000-00-00' then third_wormer_date::date else null end	   	   
       , forth_dewormer
	   , case when forth_wormer_date <> '0000-00-00' then forth_wormer_date::date else null end	   	   
       , vaccination_notes
       , referring_domain
	   , case when create_date <> '0000-00-00' then create_date::date else null end	   	   
	   , case when edit_date <> '0000-00-00' then edit_date::date else null end	   	   
       , email_login
       , heart_murmur
       , undescended_testicles
       , patellar_luxation
       , umbilical_inguinal_hernia
       , breeder_city
       , breeder_state
       , breeder_zip
       , breeder_phone2
       , breeder_phone3
       , breeder_payment_method
       , breeder_puppy_cost
       , breeder_shipping_cost
       , breeder_payment_due
       , breeder_reimbursement
	   , case when breeder_plans_to_ship_date <> '0000-00-00' then breeder_plans_to_ship_date::date else null end	   	   
       , first_name_2
       , last_name_2
       , discount
       , company_discount
       , breeder_discount
       , breeder_shipping_discount
       , sales_tax
       , balance_due
       , surprise
       , payment
       , pups_sold::int
       , last_note
       , puppy_name
       , lead_breeder_state
       , b_puppy_name
       , breeder_extra_cost
       , work_phone_ext
	   , case when departure_date <> '0000-00-00' then departure_date::date else null end	   	   
       , departure_time
       , email_valid::int
       , variety
       , b_variety
       , paperwork_received
       , auth_received
       , id_received
       , reply_received
       , second_ltg
       , email_2
       , email_2_valid::int
       , alert
       , fifth_dewormer
       , case when fifth_wormer_date <> '0000-00-00' then fifth_wormer_date::date else null end
       , forth_vac
       , case when forth_vac_date <> '0000-00-00' then forth_vac_date::date else null end
       , ear_mites
       , fleas_ticks
       , parasites
       , congestion
       , giardia
       , coccidia
       , fecal_parasites
       , replacement::int
       , adjust_commission
       , sales_issue
       , b_puppy_food
       , b_feeding_schedule
       , customer_payment_type::int
       , breeder_payment_type::int
       , qb_customer_invoice::int
       , qb_customer_payment::int
       , qb_breeder_bill::int
       , qb_breeder_payment::int
       , called_breeder::int
       , billing_email
       , billing_first_name
       , billing_last_name
       , spiff::int
       , customer::int
       , cancel_reason::int
       , petkey::int
       , petkey_cost
       , trupanion::int
       , status::int
       , \"user\"::int
       , retention_manager::int
       , breeder_id::int
       , two_dog_contact_id::int
       , account_audited::int
from contact_staging;";

if ($debug==1)
{
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
}
$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
echo "Rows affected $rowsaffected \n\n";







?>
