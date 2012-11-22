<?php

namespace TitoMiguelCosta\Flickr;
use ZendService\Flickr\Flickr as BaseFlickr;
use DOMDocument;
use Zend\Http\Request as HttpRequest;
use ZendService\Flickr\ResultSet;


class Flickr extends BaseFlickr
{
    public function userGallery($query, $gallery_id, array $options = null)
    {
        static $method = 'flickr.galleries.getPhotos';
        static $defaultOptions = array('per_page' => 10,
                                       'page'     => 1,
                                       'extras'   => 'license, date_upload, date_taken, owner_name, icon_server');


        // can't access by username, must get ID first
        if (strchr($query, '@')) {
            // optimistically hope this is an email
            $options['user_id'] = $this->getIdByEmail($query);
        } else {
            // we can safely ignore this exception here
            $options['user_id'] = $this->getIdByUsername($query);
        }
        $options['gallery_id'] = $gallery_id;

        $options = $this->prepareOptions($method, $options, $defaultOptions);

        // now search for photos
        $request = new HttpRequest;
        $request->setUri('http://api.flickr.com/services/rest/');
        $request->getQuery()->fromArray($options);
        $response = $this->httpClient->send($request);

        if ($response->isServerError() || $response->isClientError()) {
            throw new Exception\RuntimeException('An error occurred sending request. Status code: '
                                                 . $response->getStatusCode());
        }

        $dom = new DOMDocument();
        $dom->loadXML($response->getBody());

        self::checkErrors($dom);

        return new ResultSet($dom, $this);
    }
}
