<?php

namespace App\Models\Auth;
use Symfony\Component\Ldap\Ldap;

class LdapFinder
{
    /**
     * @var \Symfony\Component\Ldap\Ldap
     */
    private Ldap $connector;

    /**
     * @var string
     */
    private string $dn;

    /**
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     * @param string $dn
     */
    public function __construct(string $host, int $port, string $username, string $password, string $dn)
    {
        $this->dn = $dn;
        $this->connector = $this->connect($host, $port);
        $this->bind($username, $password);
    }

    /**
     * @param string $host
     * @param int $port
     * @return \Symfony\Component\Ldap\Ldap
     */
    private function connect(string $host, int $port): Ldap
    {
        return Ldap::create('ext_ldap', [
            'host' => $host,
            'port' => $port,
        ]);
    }

    /**
     * @param string $name
     * @param string $password
     */
    private function bind(string $username, string $password)
    {
        $this->connector->bind($username, $password);
    }

    /**
     * @return \Symfony\Component\Ldap\Entry|null
     */
    public function query(string $filter): \Symfony\Component\Ldap\Entry|null
    {
        $query = $this->connector->query($this->dn, $filter);
        $data = $query->execute();
        if ($data->count() > 0) {
            return $data[0];
        }
        return null;
    }

}