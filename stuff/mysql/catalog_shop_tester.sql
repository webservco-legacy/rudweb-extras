SELECT SQL_CALC_FOUND_ROWS A.prd_id, A.prd_iid AS pid, A.prd_tt AS pname, A.prd_dsc, A.prd_qty, A.prd_qlvl, A.prd_prc AS pprice, A.prd_plvl, PIC.pic_fn as pic_fn

-- I
 , F1.val_tt AS det1
-- , F4.opt_tt AS det1_val
-- , F2.val_tt AS det2_tt
 , G1.opt_tt AS det2
 , F3.val_tt AS det3
-- , F6.opt_tt AS det3_val
-- //I

 FROM mhs_cat_prd as A
left join mhs_cat_pic as PIC on (A.prd_id = PIC.prd_id AND PIC.pic_loc = '1')

-- II
 left join mhs_cat_val as F1 on (A.prd_id = F1.prd_id)
	-- left join mhs_cat_opt as F4 on (F4.opt_id = F1.opt_id)  
 left join mhs_cat_val as F2 on (A.prd_id = F2.prd_id) 
	 left join mhs_cat_opt as G1 on (G1.opt_id = F2.opt_id) 
 left join mhs_cat_val as F3 on (A.prd_id = F3.prd_id)
	-- left join mhs_cat_opt as F6 on (F6.opt_id = F3.opt_id) 

-- //II

 WHERE A.scat_id = '2'

-- III
 AND (
    F1.det_id = '1' 
AND F2.det_id = '2' 
AND F3.det_id = '3'
)
-- //III

-- IV 
 AND (
     F1.val_tt = 'red'
 and G1.opt_id != ''
 and F3.val_tt != '0'

 )
-- //IV

-- V
AND (
A.prd_tt REGEXP 'truck'
 OR
A.prd_dsc REGEXP 'truck'
)
-- //V

ORDER BY 
 pprice ASC, 
 A.prd_crd DESC, A.prd_crt DESC

-- LIMIT 0,2
;

SELECT FOUND_ROWS() as totalitems;