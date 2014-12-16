<?php
class ModelProductDelivery extends Model {

    public function check($data,$country){
        $tax_class_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id ='".$data."'");

        if ($tax_class_id->num_rows) {
            $tax_class_id_val = $tax_class_id->row['tax_class_id'];
        } else {
            $tax_class_id_val = "";
        }

        $tax_rate_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rule WHERE tax_class_id = ".(int)$tax_class_id_val);

        $geo_zone_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id =" . $country);

        if ($geo_zone_id->num_rows) {
            $geo_zone_id_val = $geo_zone_id->row['geo_zone_id'];
        } else {
            $geo_zone_id_val = "";
        }

        $cur_value = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code = '".$this->currency->getCode()."'");
        if ($cur_value->num_rows) {
            $cur_value_val = $cur_value->row['value'];
            $cur_value_sym = $cur_value->row['symbol_right'];
        } else {
            $cur_value_val = "";
        }

        $rate_val = "";

        foreach ($tax_rate_id->rows as $tax_rate_id_item) {
            $rate = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate WHERE geo_zone_id =" . (int)$geo_zone_id_val . " and tax_rate_id =". (int)$tax_rate_id_item['tax_rate_id']);
            if ($rate->num_rows) {
                $rate_val = $rate->row['rate'];
                return round($rate_val * $cur_value_val,2).$cur_value_sym;//$data['currencies']['value'];
            }
        }
        if ($rate_val == "") { return "Sorry, no delivery in your District"; }
    }

}