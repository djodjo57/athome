<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Rss\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Feed\Reader\Reader;
use Zend\Http\Client;
use Zend\Http\Header\SetCookie;
use Zend\Http\Cookies;
use Zend\Http\Request;
use Zend\Validator\Uri;
use Zend\Session\Container;
use Zend\Session\Storage\ArrayStorage;
use Zend\Session\SessionManager;

class IndexController extends AbstractActionController
{

    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $cat = "";
        if (isset($request->getCookie()->favcat)) {
            $cat = $request->getCookie()->favcat;
        }

        $config = array(
                'adapter' => 'Zend\Http\Client\Adapter\Proxy',
                'proxy_host' => '***',
                'proxy_port' => 8080,
                'proxy_user' => '',
                'proxy_pass' => ''
        );

        $temp = "http://feeds.abcnews.com/abcnews/worldnewsheadlines";
        $client = new Client($temp, $config);

        $reader = new Reader();

        try {
            $reader->setHttpClient($client);
            $rss = $reader->import($temp);
            $data = [
                    'title' => $rss->getTitle(),
                    'link' => $rss->getLink(),
                    'dateModified' => $rss->getDateModified(),
                    'description' => $rss->getDescription(),
                    'language' => $rss->getLanguage(),
                    'entries' => [],
            ];

            foreach ($rss as $item) {
                $edata = array(
                        'title' => $item->getTitle(),
                        'description' => $item->getDescription(),
                        'dateModified' => $item->getDateModified(),
                        'authors' => $item->getAuthors(),
                        'link' => $item->getLink(),
                        'content' => $item->getContent()
                );
                $data['entries'][] = $edata;
            }
        } catch (Exception\RuntimeException $e) {
            echo "error : " . $e->getMessage();
            exit;
        }

        // Validate all URIs
        $linkValidator = new Uri();
        $link = null;
        if ($linkValidator->isValid($rss->getLink())) {
            $link = $rss->getLink();
        }

        $categories = $rss->getCategories();
        foreach ($categories as $categorie) {
            $cats[] = $categorie["label"];
        }

        return new ViewModel(array(
                    'feed' => $data,
                    'items' => $data['entries'],
                    'categories' => array_unique($cats),
                    'category' => $cat,
            ));
    }

    /**
     * Save favorite category in a cookie
     * 
     * @return type
     */
    public function favoriteAction()
    {
        $cat = $this->params('category');

        $cookie = new SetCookie('favcat', $cat, time() + 365 * 60 * 60 * 24, "/", false);
        $this->getResponse()->getHeaders()->addHeader($cookie);

        return $this->redirect()->toRoute('home', array('category' => $cat));
    }
}
