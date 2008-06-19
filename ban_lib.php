<?php
/**
 * User access Banning Library
 *
 * @package tidbits
 * @version $Header: /cvsroot/bitweaver/_bit_tidbits/ban_lib.php,v 1.6 2008/06/19 05:02:41 lsces Exp $
 */

/**
 * User access Banning Library
 *
 * @package tidbits
 */
class BanLib extends BitBase {
	function BanLib() {				
	BitBase::BitBase();
	}

	function get_rule($ban_id) {
		$query = "select * from `".BIT_DB_PREFIX."tidbits_banning` where `ban_id`=?";

		$result = $this->mDb->query($query,array($ban_id));
		$res = $result->fetchRow();
		$aux = array();
		$query2 = "select `package` from `".BIT_DB_PREFIX."tidbits_banning_packages` where `ban_id`=?";
		$result2 = $this->mDb->query($query2,array($ban_id));
		$aux = array();

		while ($res2 = $result2->fetchRow()) {
			$aux[] = $res2['package'];
		}

		$res['packages'] = $aux;
		return $res;
	}

	function remove_rule($ban_id) {
		$query = "delete from `".BIT_DB_PREFIX."tidbits_banning` where `ban_id`=?";

		$this->mDb->query($query,array($ban_id));
		$query = "delete from `".BIT_DB_PREFIX."tidbits_banning_packages` where `ban_id`=?";
		$this->mDb->query($query,array($ban_id));
	}

	function list_rules($offset, $max_records, $sort_mode, $find, $where = '') {
		global $gBitSystem;
		if ($find) {
			$findesc = '%' . strtoupper( $find ) . '%';
			$mid = " WHERE ((UPPER(`ban_message`) LIKE ?) OR (UPPER(`title`) LIKE ?))";
			$bindvars=array($findesc,$findesc);
		} else {
			$mid = "";
			$bindvars=array();
		}

		// DB abstraction: TODO
		if ($where) {
			if ($mid) {
				$mid .= " and ($where) ";
			} else {
				$mid = "where ($where) ";
			}
		}

		$query = "select * from `".BIT_DB_PREFIX."tidbits_banning` $mid order by ".$this->mDb->convertSortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tidbits_banning` $mid";
		$result = $this->mDb->query($query,$bindvars,$max_records,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$aux = array();

			$query2 = "select * from `".BIT_DB_PREFIX."tidbits_banning_packages` where `ban_id`=?";
			$result2 = $this->mDb->query($query2,array($res['ban_id']));

			while ($res2 = $result2->fetchRow()) {
				$aux[] = $res2;
			}

			$res['packages'] = $aux;
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		$now = $gBitSystem->getUTCTime();
		$query = "SELECT `ban_id` FROM `".BIT_DB_PREFIX."tidbits_banning` WHERE `use_dates`=? AND `date_to` < ?";
		$result = $this->mDb->query($query,array('y',$now));

		while ($res = $result->fetchRow()) {
			$this->remove_rule($res['ban_id']);
		}

		return $retval;
	}

	/*
	ban_id integer(12) not null auto_increment,
	  mode enum('user','ip'),
	  title varchar(200),
	  ip1 integer(3),
	  ip2 integer(3),
	  ip3 integer(3),
	  ip4 integer(3),
	  user varchar(200),
	  date_from timestamp,
	  date_to timestamp,
	  use_dates char(1),
	  ban_message text,
	  primary key(ban_id)
	  */
	function replace_rule($ban_id, $mode, $title, $ip1, $ip2, $ip3, $ip4, $user, $date_from, $date_to, $use_dates, $message,
		$packages) {

		if ($ban_id) {
			$query = " UPDATE `".BIT_DB_PREFIX."tidbits_banning` SET
  			`title`=?,
  			`ip1`=?,
  			`ip2`=?,
  			`ip3`=?,
  			`ip4`=?,
  			`ban_user`=?,
  			`date_from` = ?,
  			`date_to` = ?,
  			`use_dates` = ?,
  			`ban_message` = ?
  			WHERE `ban_id`=?
  		";

			$this->mDb->query($query,array($title,$ip1,$ip2,$ip3,$ip4,$user,$date_from,$date_to,$use_dates,$message,$ban_id));
		} else {
			global $gBitSystem;
			$now = $gBitSystem->getUTCTime();

			$query = "INSERT INTO `".BIT_DB_PREFIX."tidbits_banning`(`mode`,`title`,`ip1`,`ip2`,`ip3`,`ip4`,`ban_user`,`date_from`,`date_to`,`use_dates`,`ban_message`,`created`)
		values(?,?,?,?,?,?,?,?,?,?,?,?)";
			$this->mDb->query($query,array($mode,$title,$ip1,$ip2,$ip3,$ip4,$user,$date_from,$date_to,$use_dates,$message,$now));
			$ban_id = $this->mDb->getOne("SELECT MAX(`ban_id`) FROM `".BIT_DB_PREFIX."tidbits_banning` WHERE `created`=?",array($now));
		}

		$query = "DELETE FROM `".BIT_DB_PREFIX."tidbits_banning_packages` WHERE `ban_id`=?";
		$this->mDb->query($query,array($ban_id));

		foreach ($packages as $package) {
			$query = "INSERT INTO `".BIT_DB_PREFIX."tidbits_banning_packages`(`ban_id`,`package`) VALUES(?,?)";

			$this->mDb->query($query,array($ban_id,$package));
		}
	}
}

$banlib = new BanLib();

?>
