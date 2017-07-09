<?php
namespace Ashvastha\Yggdrasil;

class Manager
{
	public function __construct($Admin)
	{
		if (0 and get_class($Admin) != 'Administrator')
		{
			trigger_error(__CLASS__ . '->' . __METHOD__ . '() - $Admin object was not provided.', E_USER_ERROR);
		}
	}


    public function deletePage($pageId)
    {
		$pageId = (!is_array($pageId)) ? array($pageId): $pageId;
		$result = 0;
		
		foreach ($pageId as $key => $id)
		{
			if (is_numeric($id))
			{
				$pageId[$key] = intval($id);
			}
			else
			{
				trigger_error(__CLASS__ . '->' . __METHOD__ . "() received a non-numeric \$pageId of $pageId", E_USER_WARNING);
			}
		}

        Global $Database;

        foreach ($pageId as $id)
        {
            //todo update to a single query
            $r = $Database->query("SELECT * FROM `yggdrasil` WHERE `id` = ? LIMIT 1;", array($id));
            $r = array_shift($r->ToArray());
            if (!empty($r)) $rows[] = $r;
        }

		foreach ($rows as $row)
		{
			if (!$row['portal'])
			{
				trigger_error(__CLASS__ . '->' . __METHOD__ . "() cannot delete page $pageId as it has no portal.", E_USER_WARNING);
			}
			elseif (0)
			{
				//todo admin acl security check
			}
			else
			{
				//change deletePortal, deleteAdmin, deleteTimestamp

				$statement = $Database->createStatement("UPDATE `yggdrasil` SET `portal` = ?, `deleteAgent`=?, `deleteTimestamp`=?, `deletePortal`=? WHERE `id`=?;",
                                array(67, 42, time(), intval($row['portal']), intval($row['id'])));
                $r = $statement->execute();
                $result = $result + $r->getAffectedRows();
                unset($r);
			}
		}
		
		unset($rows);
		
		return $result;
    }


    public function getPageList($portalId = 0)
    {
        if (empty($portalId)) return array();
        $portalId = intval($portalId);

        Global $Database;

        $result = $Database->query("SELECT `id`, `path`, `portal`, `title`, `templateName` FROM `yggdrasil` WHERE `portal` = ? ORDER BY `path`;", array($portalId));
        $result = $result->ToArray();

        return (count($result))? $result : array();
    }
}