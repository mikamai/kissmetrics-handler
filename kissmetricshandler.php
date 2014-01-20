<?php

if (!defined('_PS_VERSION_'))
  exit;

class KISSmetricsHandler extends Module
{
  private $_html = '';

  public function  __construct()
  {
    $this->name = 'kissmetricshandler';
    $this->tab = 'front_office_features';
    $this->version = '1.0';
    $this->author = 'Gianluca Randazzo';
    $this->need_instance = 0;
    $this->ps_version_compliancy = array('min' => '1.4');
    parent::__construct();
    $this->displayName = $this->l('KISSmetrics handler');
    $this->description = $this->l('Let you handle KISSmetrics stuff.');
  }

  public function install()
  {
    // if (Shop::isFeatureActive())
    //   Shop::setContext(Shop::CONTEXT_ALL);
    return parent::install() &&
      $this->registerHook('header') &&
      $this->registerHook('authentication') &&
      $this->registerHook('orderconfirmation') &&
      $this->registerHook('createaccount') &&
      $this->registerHook('productfooter');
  }

  public function uninstall()
  {
    return parent::uninstall() &&
      $this->unregisterHook('header') &&
      $this->unregisterHook('authentication') &&
      $this->unregisterHook('orderconfirmation') &&
      $this->unregisterHook('createaccount') &&
      $this->unregisterHook('productfooter');
  }

  public function hookHeader($params)
  {
    $kissmetrics_head = $this->display(__FILE__, 'head.tpl').
                        $this->display(__FILE__, 'identifyusers.tpl').
                        $this->display(__FILE__, 'startedpurchase.tpl').
                        $this->display(__FILE__, 'viewedsignupform.tpl');

    if (isset($_SESSION)) {
      if (isset($_SESSION['activity'])) {
        $kissmetrics_head .= $this->display(__FILE__, 'activity.tpl');
        unset($_SESSION['activity']);
      }
      if (isset($_SESSION['signedup'])) {
        $kissmetrics_head .= $this->display(__FILE__, 'signedup.tpl');
        unset($_SESSION['signedup']);
      }
    }

    return $kissmetrics_head;
  }

  public function hookAuthentication($params)
  {
    if (!isset($_SESSION)) session_start();
    $_SESSION['activity'] = true;
  }

  public function hookOrderConfirmation($params)
  {
    global $smarty, $cookie;
    $purchased_products = $params['objOrder']->getProducts();
    foreach ($purchased_products as $key => &$product) {
      $product_object = new Product($product['product_id']);
      $category = new Category($product_object->id_category_default, $cookie->id_lang);
      $product['category'] = $category->name;
    }
    $smarty->assign('purchased_products', $purchased_products);
    return $this->display(__FILE__, 'purchased.tpl');
  }

  public function hookCreateAccount($params)
  {
    if (!isset($_SESSION)) session_start();
    $_SESSION['signedup'] = true;
  }

  public function hookProductFooter($params)
  {
    return $this->display(__FILE__, 'viewedpotentialpurchase.tpl');
  }

}
