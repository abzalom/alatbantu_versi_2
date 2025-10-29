alter table opd_tag_otsuses 
    modify volume decimal(32,2) null,
    modify sumberdana enum(
        'PAD',
        'DAU',
        'DBH',
        'DAK Fisik',
        'DAK Non Fisik',
        'Otsus 1%',
        'Otsus 1,25%',
        'DTI',
        'Tambahan DTI Migas',
        'Belanja KL',
        'Lainnya',
        'Tidak Ada'
    ) null,
    modify alias_dana enum('bg','sg','dti') null;