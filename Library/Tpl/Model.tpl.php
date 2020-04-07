<?php /**
 *@author dbBuilder
 */
namespace __NAMESPACE__;

class __MODEL__  extends Model{
    /**
     *@param $__UC_TABLE__Bean  \App\Beans\__TABLE__Bean
     */
    public function createTabGameByBean($__UC_TABLE__Bean) {
        return $this->add($__UC_TABLE__Bean->asArray());
    }
    /**
     *@param $id int
     *@return   \App\Beans\__TABLE__Bean
     */
    public function getTabGameById($id) {
        $__UC_TABLE__Bean = new   \__NAMESPACE__\__TABLE__Bean();
        $data = $this->where(['id' => $id])->find();
        if($data) {
            $__UC_TABLE__Bean->setAttributes($data);
        }
        return  $__UC_TABLE__Bean;
    }
}
