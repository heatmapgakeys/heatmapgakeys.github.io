<?php
namespace App\Services\Payment\Ecommerce;

use Stripe\StripeClient;

class StripeServiceEcommerce implements StripeServiceEcommerceInterface {

    public $secret_key;
    public $publishable_key;    
    public $description;
    public $amount;
    public $action_url; 
    public $currency;
    public $img_url;
    public $title;
    public $button_lang='';
    public $stripe_billing_address='0';
    public $secondary_button=false;

    function __construct(){
    }

    function set_button(){
    
        if(strtoupper($this->currency)=='JPY' || strtoupper($this->currency)=='VND') $amount=$this->amount;
        else $amount=$this->amount*100;
        
        $button="";
        $stripe_lang = !empty($this->button_lang)?$this->button_lang:__("Pay with Stripe");
        $billing_address="";
        if($this->stripe_billing_address=='1') $billing_address = "data-billing-address='true'";
        $hide_me = $this->secondary_button ? 'display:none;' : '';
        
        $button.="<form action='{$this->action_url}' method='POST' style='".$hide_me."' id='stripePaymentForm01'>
            <script
            src='https://checkout.stripe.com/checkout.js' class='stripe-button'
            data-key='{$this->publishable_key}'
            data-image='{$this->img_url}'
            data-name='{$this->title}'
            data-currency='{$this->currency}'
            data-description='{$this->description}'
            data-amount='{$amount}'
            data-label='{$stripe_lang}'
            {$billing_address}>
            </script>
        </form>";

        if($this->secondary_button)
        $button.="
        <a href='#' class='list-group-item list-group-item-action flex-column align-items-start' id='stripe_clone' onclick=\"document.querySelector('#stripePaymentForm01 .stripe-button-el').click();\">
            <div class='d-flex w-100 align-items-center'>
              <small class='text-muted'><img class='rounded' width='60' height='60' src='".asset('assets/images/stripe.png')."'></small>
              <h6 class='mb-1'>".$stripe_lang."</h6>
            </div>
        </a>";  

        return $button;
        
    }
    
        
        
    public function stripe_payment_action($amount='',$currency='',$description='')
    {       
        $response=array();      


        $description = urldecode($description);

        if(strtoupper($currency)=='JPY' || strtoupper($currency)=='VND')$amount=$amount;
        else $amount=$amount*100;
            
        try
        {
        
            $stripe = new StripeClient($this->secret_key);   
            $charge = $stripe->charges->create(array(
                "amount" => $amount,
                "currency" => $currency,
                "card" => $_POST['stripeToken'],
                "description" => $description
            ));
            
            
            $email  = $_POST['stripeEmail'];
            
            $response['status']="Success";
            $response['email']=$email;
            $response['charge_info']=$charge;

            return $response;
        
        }
        
        catch(\Stripe\Exception\CardException $e) {
            $response['status'] ="Error";
            $response['message'] ="Stripe_CardError"." : ".$e->getMessage();
            return $response;
        }
        
         catch (\Stripe\Exception\InvalidRequestException $e) {
            $response['status'] ="Error";
            $response['message'] ="Stripe_InvalidRequestError"." : ".$e->getMessage();
            return $response;
        
        } catch (Stripe_AuthenticationError $e) {
            $response['status'] ="Error";
            $response['message'] ="Stripe_AuthenticationError"." : ".$e->getMessage();
            return $response;
        
        } catch (\Stripe\Exception\AuthenticationException $e) {
            $response['status'] ="Error";
            $response['message'] ="Stripe_ApiConnectionError"." : ".$e->getMessage();
            return $response;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $response['status'] ="Error";
            $response['message'] ="Stripe_Error"." : ".$e->getMessage();
            return $response;
          
        } catch (Exception $e) {
            $response['status'] ="Error";
            $response['message'] ="Stripe_Error"." : ".$e->getMessage();
            return $response;
        }
            
      }


}
