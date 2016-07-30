<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\Framework\Controller\Exception\Redirect404Exception;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;

/**
 * Class AdminController.
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/admin")
 */
class AdminController extends AbstractController {

    /**
     * @return CityRepository current repository.
     */
    private function getRepository (): CityRepository {
        return new CityRepository();
    }

    /**
     * @return array
     *
     * @throws Redirect404Exception
     *
     * @path("/city")
     */
    public function cityRoute () : array {
        return [
            'cities' => $this->getRepository()->getAll()
        ];
    }

}