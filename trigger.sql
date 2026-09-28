CREATE TRIGGER upd_trash_supplier after UPDATE ON per_supplier
FOR EACH ROW 
BEGIN
	IF NEW.trash <> OLD.trash THEN
		 UPDATE merek
		 SET trash = NEW.trash
		 WHERE
		 supplier_id = NEW.id;

		 UPDATE produk
		 SET trash = NEW.trash
		 WHERE
		 supplier_id = NEW.id;

		 UPDATE produk_outdoor
		 SET trash = NEW.trash
		 WHERE
		 supplier_id = NEW.id;

		 UPDATE produk_indoor_1
		 SET trash = NEW.trash
		 WHERE
		 supplier_id = NEW.id;
		 
		 UPDATE diskon_pembelian
		 SET trash = NEW.trash
		 WHERE
		 supplier_id = NEW.id; 

		 UPDATE diskon_pembelian_supplier
		 SET trash = NEW.trash
		 WHERE
		 supplier_id = NEW.id; 
	 
	END IF;
END;
 
 CREATE TRIGGER upd_trash_produk after UPDATE ON produk
 FOR EACH ROW 
 BEGIN
	IF NEW.trash <> OLD.trash THEN
		UPDATE produk_per_supplier
		SET trash = NEW.trash
		WHERE
		produk_id = NEW.id;
	END IF;
 END;

#-- Trigger untuk histori setiap kali ada tambah pada tabel
CREATE TRIGGER address_his_insert
AFTER INSERT ON address
FOR EACH ROW
BEGIN
    INSERT INTO address_history (address_id, action, dtime, data_lama, data_baru, oleh_id, oleh_nama)
    VALUES (NEW.id, 'INSERT', NOW(), NULL, CONCAT('nama: ', NEW.nama, ', alias: ', NEW.alias, ', tlp: ', NEW.tlp, ', email: ', NEW.email, ', alamat: ', NEW.alamat, ', kelurahan: ', NEW.kelurahan, ', kecamatan: ', NEW.kecamatan, ', kabupaten: ', NEW.kabupaten, ', propinsi: ', NEW.propinsi, ', kodepos: ', NEW.kodepos, ', npwp: ', NEW.npwp, ', no_ktp: ', NEW.no_ktp), NEW.oleh_id, NEW.oleh_nama);
END;

#-- Trigger untuk histori setiap kali ada pembaruan pada tabel
CREATE TRIGGER address_his_update
AFTER UPDATE ON address
FOR EACH ROW
BEGIN
    INSERT INTO address_history (address_id, action, dtime, data_lama, data_baru, oleh_id, oleh_nama)
    VALUES (NEW.id, 'UPDATE', NOW(), 
  	CONCAT('nama: ', OLD.nama, ', alias: ', OLD.alias, ', tlp: ', OLD.tlp, ', email: ', OLD.email, ', alamat: ', OLD.alamat, ', kelurahan: ', OLD.kelurahan, ', kecamatan: ', OLD.kecamatan, ', kabupaten: ', OLD.kabupaten, ', propinsi: ', OLD.propinsi, ', kodepos: ', OLD.kodepos, ', npwp: ', OLD.npwp, ', no_ktp: ', OLD.no_ktp), 
	  CONCAT('nama: ', NEW.nama, ', alias: ', NEW.alias, ', tlp: ', NEW.tlp, ', email: ', NEW.email, ', alamat: ', NEW.alamat, ', kelurahan: ', NEW.kelurahan, ', kecamatan: ', NEW.kecamatan, ', kabupaten: ', NEW.kabupaten, ', propinsi: ', NEW.propinsi, ', kodepos: ', NEW.kodepos, ', npwp: ', NEW.npwp, ', no_ktp: ', NEW.no_ktp), NEW.oleh_id, NEW.oleh_nama);
END;