<?php


namespace MyObject\common\user\values;


use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

/**
 * Class MoUserId
 * Класс описывающтий ID пользователей для сервисов MyObject
 * @package MyObject\common\user\values
 */
class MoUserId
{
    private $id;

    /**
     * MoUserId constructor.
     * @param string|null $id
     * @throws \Exception
     */
    public function __construct(string $id = null)
    {

        if ($id === null) {
            $this->id = Uuid::uuid4();
        } elseif(Uuid::isValid($id)){
            $this->id = (Uuid::fromString($id));
        } else{
            throw new InvalidUuidStringException();
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id->getHex();
    }

    public function __toString()
    {
        return $this->id->getHex();
    }


}
