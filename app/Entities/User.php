<?php

namespace ApiArchitect\Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use LaravelDoctrine\ACL\Roles\HasRoles;
use LaravelDoctrine\ACL\Mappings as ACL;
use ApiArchitect\Abstracts\EntityAbstract;
use Illuminate\Contracts\Auth\CanResetPassword;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use ApiArchitect\Contracts\DoctrineLoggableContract;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionContract;
use LaravelDoctrine\ORM\Contracts\Auth\Authenticatable as AuthenticatableContract;

/**
 * Class User
 *
 * @package ApiArchitect\Entities
 * @author James Kirkby <me@jameskirkby.com>
 *
 * @ORM\Entity
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 * @ORM\Entity(repositoryClass="ApiArchitect\Repositories\User\UserRepository")
 * @ORM\Table(name="users", indexes={@ORM\Index(name="search_idx", columns={"email"})})
 */
class User extends EntityAbstract implements AuthenticatableContract, HasRolesContract, HasPermissionContract, DoctrineLoggableContract, CanResetPassword
{

    use AuthenticatableTrait, HasRoles, HasPermissions, CanResetPasswordTrait;

    /**
     * @var
     *
     * @ORM\Column(type="string", length=255, unique=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     */
    protected $email;

    /**
     * @ACL\HasRoles()
     * @var \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Role[]
     */
    protected $roles;

    /**
     * @ACL\HasPermissions
     */
    public $permissions;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns user name, required for Doctrine behaviours loggable
     * @return mixed
     */
    public function getUserName()
    {
        return $this->getName();
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:ApiArchitect\Entities\User,username',
            'password' => 'required|confirmed|min:8',
        ]);
    }
}