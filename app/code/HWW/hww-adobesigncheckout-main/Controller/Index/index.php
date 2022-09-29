<?php

namespace Harriswebworks\AdobeSignCheckout\Controller\Index;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Harriswebworks\AdobeSignCheckout\Logger\Logger as NLogger;

use Magento\Checkout\Model\Session as CheckoutSession;


class Index extends \Magento\Framework\App\Action\Action
{




  /**
   * @var \Magento\Framework\View\Result\PageFactory
   */
  protected $_pageFactory;
  protected $checkoutSession;

  protected $_adobeSignFactory;

  /**
   * @var \Magento\Customer\Model\Session
   */
  protected $_customerSession;

  /**
   * @var \Magento\Store\Model\StoreManagerInterface
   */
  protected $_storeManager;

  /**
   * @var ScopeConfigInterface
   */
  protected $_scopeConfig;
  /**
   * @var JsonFactory
   */
  protected $_resultJsonFactory;

  /**
   * @var \Magento\Checkout\Model\Cart
   */
  protected $cart;

  /**
   * @var \Magento\Framework\Filesystem\DirectoryList
   */
  protected $directoryList;

  /**
   * @var \Magento\Theme\Block\Html\Header\Logo
   */
  protected $_logo;

  /**
   * @var NLogger
   */
  protected $nLogger;


  public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    ScopeConfigInterface $scopeConfig,
    \Magento\Framework\View\Result\PageFactory $pageFactory,
    JsonFactory $resultJsonFactory,
    \Magento\Checkout\Model\Cart $cart,
    \Magento\Framework\Filesystem\DirectoryList $directoryList,
    \Magento\Theme\Block\Html\Header\Logo $logo,
    \Harriswebworks\AdobeSignCheckout\Model\AdobeSignFactory $adobeSignFactory,
    CheckoutSession  $checkoutSession,
    NLogger $nLogger
  ) {
    $this->_pageFactory = $pageFactory;
    $this->_storeManager = $storeManager;
    $this->_scopeConfig = $scopeConfig;
    $this->_customerSession = $customerSession;
    $this->_resultJsonFactory = $resultJsonFactory;
    $this->cart = $cart;
    $this->directoryList = $directoryList;
    $this->_logo = $logo;
    $this->nLogger = $nLogger;
    $this->_adobeSignFactory = $adobeSignFactory;
    return parent::__construct($context);
    $this->checkoutSession = $checkoutSession;
  }

  /**
   * Get logo image URL
   *
   * @return string
   */
  public function getLogoSrc()
  {
    return $this->_logo->getLogoSrc();
  }

  /**
   * Undocumented function
   *
   * @param $path
   * @return void
   */
  public function getConfigValue($path)
  {
    return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
  }

  /**
   * Undocumented function
   *
   * @return void
   */
  public function getCustomerId()
  {
    return $this->_customerSession->getCustomer()->getId(); //Print current customer ID
  }

  /**
   * Undocumented function
   *
   * @return void
   */

  public function getCustomerGroupId()
  {
    return $this->_customerSession->getCustomer()->getGroupId(); //Print current customer group ID
  }

  /**
   * Undocumented function
   *
   * @return void
   */
  public function getCustomerName()
  {
    return $this->_customerSession->getCustomer()->getFirstname(); //Print current customer name
  }

  /**
   * Undocumented function
   *
   * @return void
   */
  public function getCustomerEmail()
  {
    return $this->_customerSession->getCustomer()->getEmail(); //Print current customer name
  }

  /**
   * Undocumented function
   *
   * @return void
   */
  public function IsCustomerLoggedIn()
  {
    return $this->_customerSession->getCustomer()->isLoggedIn(); //Print current loggedin
  }
  public function getQouteId()
  {
    return $this->checkoutSession->getQuote()->getId();
  }

  // public function getQuote()
  // {
  //   return $this->cartHelper->getQuote();
  // }

  /**
   * Undocumented function
   *
   * @param [type] $itemsVisible
   * @param [type] $shippingaddress
   * @return void
   */
  protected function getProductHtml($itemsVisible, $shippingaddress)
  {
    $termsandcondition = $this->getConfigValue('hww_AdobeSignCheckout/adobesignconf/termsandconditions');

    $product = '';
    foreach ($itemsVisible as $item) {
      $product .= '<tr>' . '<td>' . $item->getName() . '</td>' . '<td>' . $item->getSku() . '</td>' . '<td>' . $item->getQty() .  '<td>' . $item->getPrice() . '</td>' . '</td></tr>';
    }

    $html = '
    <html>
    <head>
    <style>
    body {font-family: sans-serif;
      font-size: 9pt;

    }
    h5, p {	margin: 0pt;
    }
    table.items {
      font-size: 9pt;
      border-collapse: collapse;
      border:0;

    }
    td { vertical-align: top;border:0;
    }
    table thead td {
      text-align: left;
      border:0;
      border-top: 3px solid #000000;
      border-bottom: 3px solid #000000;
    }
    table tfoot td {
      text-align: left;
    }
    .barcode {

      margin: 0;
      vertical-align: top;
      color: #000000;
    }
    .barcodecell {

      vertical-align: middle;
      padding: 0;
    }
    </style>
    </head>
    <body>

    <htmlpagefooter name="myfooter">


    <div style="font-size: 16px; padding: 0 !important;">
    {{Signature1_es_:signer1:signature}} <br/>
    DATE: {{Date1_es_:signer1:date}}
    <div style="padding: 5px 0 0 15px">' . $shippingaddress['firstname'] . " " . $shippingaddress['lastname'] . '</div>
    <div style="padding-left: 15px">' . $shippingaddress['company'] . '</div>
    <div style="padding-left: 15px">' . $shippingaddress['street'] . '</div>
    <div style="padding-left: 15px">' . $shippingaddress['city'] . ", " . $shippingaddress['region'] . ", " . $shippingaddress['postcode'] . '</div>
    </div>
    </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="myfooter" value="on" />

    <table class="header" width="100%" cellpadding="0" border="0">
    <tr>
    <td><img src="' . $this->getLogoSrc() . '" ></td>
    <td style="border-left:1px solid #000000; padding-left:15px; padding-top:20px">Fulfilled by The Boat Locker<br />
    706 Howard Ave<br />
    Bridgeport, CT 06605<br />
    203 259 7808<br />
    info@boatlocker.com
    </td>
    </tr>
    </table>
    <h2 style="border-top:3px solid #000000; border-bottom:1px solid #000000;padding:5px 0; ">
    </h2>
    <div style="padding-left:30px;padding-bottom:15px;">
    ' . $shippingaddress['firstname'] . " " . $shippingaddress['lastname'] . '<br />
    ' . $shippingaddress['company'] . '<br />
    ' . $shippingaddress['street'] . '<br />
    ' . $shippingaddress['city'] . ", " . $shippingaddress['region'] . ", " . $shippingaddress['postcode'] . '<br />
    T: ' . $shippingaddress['telephone'] . '<br />
    E: ' . $shippingaddress['email'] . '<br />
    </div>
    <table class="items" width="100%" cellpadding="8" border="1">
    <thead>
    <tr>
    <td >Product Name</td>
    <td>SKU</td>
    <td>QTY</td>
    <td>Price</td>
    </tr>
    </thead>
    <tbody>
    ' . $product . '


    </tbody>
    </table>

    <div style="margin-top:50px;">
    ' . $termsandcondition . '
    </div>


    </body>
    </html>
    ';

    return $html;
  }

  public function getQuoteId(){
    $quoteData=$this->cart->getQuote()->getData();
    return $quoteData["items"][0]->getData('quote_id');
    
  }

  public function execute()
  {
      
    // var_dump();
    // exit;
    $hww_adobe_sign = $this->_adobeSignFactory->create();
    $collection = $hww_adobe_sign->getCollection();
    // foreach ($collection as $item) {
    //   echo "<pre>";
    //   print_r($item->getData());
    //   echo "</pre>";
    // }
    // exit();


    $apiBaseURL = $this->getConfigValue('hww_adobesigncheckout/apiconf/apiBaseURL');
    $userEmail = $this->getConfigValue('hww_adobesigncheckout/apiconf/userEmail');
    $authKey = $this->getConfigValue('hww_adobesigncheckout/apiconf/authKey');
    $result = $this->_resultJsonFactory->create();

    // get array of all items what can be display directly
    $itemsVisible = $this->cart->getQuote()->getAllVisibleItems();

    // Get Shipping Information
    $shippingaddress = $this->cart->getQuote()->getShippingAddress();

    $productHtml = $this->getProductHtml($itemsVisible, $shippingaddress);

    // var_dump($html);exit;
    $mpdf = new \Mpdf\Mpdf([
      'margin_left' => 15,
      'margin_right' => 15,
      'margin_top' => 0,
      'margin_bottom' => 25,
      'margin_header' => 0,
      'margin_footer' => 10,
      'showBarcodeNumbers' => FALSE
    ]);

    $mpdf->WriteHTML($productHtml);


    $filepath = $this->getFilePath();
    $directory = pathinfo($filepath, PATHINFO_DIRNAME);

    // Generate Directory if not exists

    if (!is_dir($directory)) {
      mkdir($directory, 0777, true);
    }

    $mpdf->Output($filepath, 'F');

    $transientDocumentId = $this->getTransientDocumentId($filepath, $apiBaseURL, $authKey, $userEmail);

    if ($transientDocumentId) {
      $lastSentAgreementID = json_decode($this->createSendAgreement($transientDocumentId, $apiBaseURL, $authKey, $userEmail))->id;

      if ($lastSentAgreementID) {
        // Need to insert
      }

      sleep(2);
      $signingUrl = ((array)((((array)((array) json_decode($this->getSigningUri($lastSentAgreementID, $apiBaseURL, $authKey, $userEmail))->signingUrlSetInfos)[0])["signingUrls"])[0]))["esignUrl"];


      $data = array(
        'lastSentAgreementID' => $lastSentAgreementID,
        'output' => $signingUrl,
      );

      $hww_adobe_sign->addData([
        "user_id" => $this->getCustomerId(),
        "agreement_id" => $lastSentAgreementID,
        "quote_id" => $this->getQuoteId(),
        "agreement_status" => "not configured yet"
      ]);
      $hww_adobe_sign->save();


      $result->setData($data);
      return $result;
    } else {
      $result->setData([
        'error' => 'transientDocumentId not found'
      ]);
      return $result;
    }
  }

  /**
   * Undocumented function
   *
   * @param [type] $apiBaseURL
   * @param [type] $filepath
   * @return void
   */
  public function getTransientDocumentId($filepath, $apiBaseURL, $authKey, $userEmail)
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $apiBaseURL . 'api/rest/v6/transientDocuments',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('File-Name' => 'response.pdf', 'File' => new \CURLFILE($filepath)),
      CURLOPT_HTTPHEADER => array(
        'Content-Type' => 'multiform/form-data',
        // 'Content-Type: application/x-www-form-urlencoded',
        'x-api-user:  email:' . $userEmail . '',
        'Authorization: Bearer ' . $authKey . ''
      ),
    ));
    // var_dump($userEmail);
    $response = curl_exec($curl);
    curl_close($curl);
    // var_dump($response);
    // exit;
    $transientId =  json_decode($response)->transientDocumentId;

    return $transientId;
  }
  /**
   * Undocumented function
   *
   * @param [type] $transientDocumentId
   * @param [type] $apiBaseURL
   * @return void
   */
  public function createSendAgreement($transientDocumentId, $apiBaseURL, $authKey, $userEmail)
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $apiBaseURL . 'api/rest/v6/agreements',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
      "fileInfos": [
        {
          "transientDocumentId": "' . $transientDocumentId . '"
        }
       ],
      "name": "API Send Transient Test Agreement 287",
      "participantSetsInfo": [
        {
          "memberInfos": [
            {
              "email": "' . $this->getCustomerEmail() . '"
            }
          ],
          "order": 1,
          "role": "SIGNER"
        }
      ],
      "signatureType": "ESIGN",
      "externalId": {
        "id": "NA2Account_1655335679"
      },
      "state": "IN_PROCESS"
      }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'x-api-user:  email:' . $userEmail . '',
        'Authorization: Bearer ' . $authKey . ''
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  public function getSigningUri($lastSentAgreementID, $apiBaseURL, $authKey, $userEmail)
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $apiBaseURL . '/api/rest/v6/agreements/' . $lastSentAgreementID . '/signingUrls',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'x-api-user:  email:' . $userEmail . '',
        'Authorization: Bearer ' . $authKey . ''
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  protected function getFilePath()
  {
    return $this->directoryList->getPath('media') . DIRECTORY_SEPARATOR . 'adobesignagreement' . DIRECTORY_SEPARATOR . $this->getCustomerId() . '.pdf';
  }

  public function logMessage($msg)
  {
    return $this->nLogger->info($msg);
  }
}
