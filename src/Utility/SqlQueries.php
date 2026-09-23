<?php

namespace Drupal\senyal_vida\Utility;

use Civi\Api4\Contact;

final class SqlQueries
{
    private function __construct()
    {
        // Prevent instantiation
    }

    public static function getContactIfExist($cid, $hash)
    {

        $contacts = Contact::get(FALSE)
        ->addSelect('*', 'address.*', 'country.*')
        ->addJoin('Address AS address', 'LEFT')
        ->addJoin('Country AS country', 'LEFT')
        ->addWhere('id', '=', intval($cid))
        ->addWhere('hash', '=', $hash)
        ->execute();

        return $contacts;
    }
}