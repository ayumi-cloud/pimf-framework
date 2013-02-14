<?php
/**
 * Pimf
 *
 * PHP Version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://krsteski.de/new-bsd-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to gjero@krsteski.de so we can send you a copy immediately.
 *
 * @copyright Copyright (c) 2010-2011 Gjero Krsteski (http://krsteski.de)
 * @license http://krsteski.de/new-bsd-license New BSD License
 */

/**
 * @package Pimf_Session_Storages
 * @author Gjero Krsteski <gjero@krsteski.de>
 */
class Pimf_Session_Storages_Dba
  extends Pimf_Session_Storages_Storage
  implements Pimf_Session_Storages_Cleaner
{
  /**
   * @var Pimf_Cache_Storages_Dba
   */
  private $dba;

  /**
   * @param Pimf_Cache_Storages_Dba $dba
   */
  public function __construct(Pimf_Cache_Storages_Dba $dba)
  {
    $this->dba = $dba;
  }
  /**
   * Load a session from storage by a given ID.
   * @param string $id
   * @return array|mixed
   */
  public function load($id)
  {
    return $this->dba->get($id);
  }

  /**
   * Save a given session to storage.
   * @param array $session
   * @param array $config
   * @param bool $exists
   */
  public function save($session, $config, $exists)
  {
    $this->dba->put($session['id'], $session, $config['lifetime']);
  }

  /**
   * @param string $id
   */
  public function delete($id)
  {
    $this->dba->forget($id);
  }

  /**
   * Delete all expired sessions from persistent storage.
   * @param int $expiration
   * @return mixed|void
   */
  public function clean($expiration)
  {
    $this->dba->clean();

    if (filemtime($this->dba->getFile()) < $expiration) {
      $this->dba->flush();
    }
  }
}
