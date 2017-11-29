<?php

namespace Model\Entity;

use Dibi\DateTime;
use LeanMapper;

/**
 * @property int $id
 * @property string $login
 * @property string $email
 * @property string $name
 * @property string $slug
 * @property string $role
 * @property string|null $password
 * @property string $firstname
 * @property string $lastname
 * @property-read int|null $rating m:useMethods
 * @property-read DateTime $dateRegistered
 */
class User extends LeanMapper\Entity {


}
