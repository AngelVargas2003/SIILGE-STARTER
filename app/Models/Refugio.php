<?php

    namespace App\Models;

    use App\Mail\ReporteMesMailable;
    use App\Models\Caer\Caer_Activo;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Mail;
    use Intervention\Image\Facades\Image;

    class Refugio extends Model
    {
        protected $table = 'Refugio';
        protected $primaryKey = 'id';
        protected $fillable = ['Nombre'];
        public $timestamps = false;
        public static function RefugioRegionTotal($Ano)
        {
            $r = collect(DB::select(DB::raw("
            select *, t.RGob+t.ROng as RT from (
            select R2.Region as 'RRegion' ,IAR.Estado as 'REstado', sum(if(IAR.gobong=1,1,0) ) as 'RGob',sum(if(IAR.gobong=2,1,0)) as 'ROng'  from  Refugio as r inner join Refugio_Region RR on r.idRefugio = RR.idRefugio
            inner  join Informacion_Adicional_Refugio IAR on r.idRefugio = IAR.idRefugio
            inner  join Region R2 on RR.idRegion = R2.idRegion_Refugio
            inner join Refugio_Activo as Ra on Ra.Refugio_id=r.idRefugio
            where  RR.idRegion in (3,4,5,6,7)  and IAR.Estado_Refugio ='Activo'
            and Ra.status=1 and Ra.ano='$Ano'
            group by  Region, Estado)as t

            ")));
            $RGob = 0;
            $ROng = 0;
            $RT = 0;
            $RGob = $r->sum(function ($item) {
                return (int)$item->RGob;
            });
            $ROng = $r->sum(function ($item) {
                return (int)$item->ROng;
            });
            $RT = $r->sum(function ($item) {
                return (int)$item->RT;
            });
            $r->push((object)array(
                "RRegion" => "",
                "REstado" => "Total",
                "RGob" => $RGob,
                "ROng" => $ROng,
                "RT" => $RT));
            return $r->toArray();
        }
        public function RefugioActivoAno()
        {
            return $this->hasMany(RefugioActivo::class, 'Refugio_id', 'idRefugio');
        }
        public function scopeRefugiosActivosAno($q,$ano)
        {
            return $q->whereHas("RefugioActivoAno",function($q)use($ano){
                return $q->where("ano",$ano);

            });
        }


        public function CaerActivoAno()
        {
            return $this->hasMany(Caer_Activo::class, "Refugio_id", "idRefugio");
        }
        public function ActivoAno($ano)
        {
            if ($this->idRefugio == 42) {
                return 1;
            }
            $st = $this->RefugioActivoAno()->where("ano", "=", $ano)->first();
            return !$st ? 0 : $st->status;
        }

		public function Region()
        {
            return $this->belongsToMany('App\Models\Region', 'Refugio_Region', 'idRefugio', 'idRegion')->withPivot('pertenece');
        }
        public static function Usuariasacceso($arreglo, $fecha)
        {
            DB::enableQueryLog();
            if (count($arreglo) == 0) {
                $arreglo[] = Auth::user()->Refugio()->first()->idregion();
            }
            return $a = Refugio::whereHas('Region', function ($q) use ($arreglo) {
                $q->whereIn('idRegion', $arreglo);
            })->get()->load(['Usuarias.Historial' => function ($q) use ($fecha) {
                $q->whereDate("fecha", '=', $fecha);
            }]);
            //return dd(DB::getQueryLog());
            /*
            return Refugio::whereHas('Region',function($q) use($arreglo){
                    $q->whereIn('idRegion',$arreglo);
                })->with('Usuarias.Historial')->whereHas('Usuarias.Historial',function($q2) use($fecha){
                    $q2->whereDate("fecha",'=',"2015-10-20");
                })->get();*/
            /*return Auth::user()->Refugio()->first()->Usuarias()->whereHas('Historial',function($q){
                $q->whereDate('fecha', '=', Carbon::today()->toDateString());
            })->get()->with('Refugio.Usuarias.Historial');*/
            /*	return Auth::user()->with('Refugio')->get();
                }else{
                    return HistorialUsuaria::whereDate('fecha', '=', Carbon::today()->toDateString())->get();
            */
        }
        public function DatosGeneral()
        {
			return $this->hasMany('App\Models\Ingresos\DatosGenerales', 'idRefugio', 'idRefugio');
		}
		public function Usuarias()
		{
			return $this->hasMany('App\Models\User', 'Refugio_idRefugio', 'idRefugio');
		}
		public function Caer()
		{
			return $this->hasMany('App\Models\Caer\Caer', 'Refugio_id');
		}
		public function Caerid()
		{
			return $this->Caer()->first()->id;
		}
		public function Informacionadicional()
		{
			return $this->hasOne('App\Models\InformacionAdicionalRefugio', 'idRefugio', 'idRefugio');
		}
		public function idregion()
		{
			return $this->Region()->where('pertenece', '=', '1')->first()->pivot->idRegion;
		}
		public function scopeRefugioxRegion($q, $idRegion)
        {
            return $q->whereHas("Region", function ($q) use ($idRegion) {
                $q->where("pertenece", "=", "1")->where("idRegion", $idRegion);
            });
        }
        public function scopeRefugioRegionPertenece($q)
        {
            $a = $q->with(["Region" => function ($qq) {
                return $qq->where("pertenece", "=", "1");
            }]);
            return $a;
        }
        public function IsActivo()
        {
            if ($this->idRefugio == 42) {
                return true;
            }
            if (($this->Informacionadicional->Estado_Refugio == "Activo")) {
                return true;
            } else {
                return false;
            }
        }
        public function IsSIILGE()
        {
            if (($this->idRefugio == 42)) {
                return true;
            } else {
                return false;
            }
        }
        public function nombre()
        {
            return $this->Nombre;
        }
        public function scopeRefugiosactivos($q)
        {
            $Refugio = $q->getModel();
            return $Refugio->whereHas('Informacionadicional', function ($q) {
                $q->where("Estado_Refugio", "=", "Activo");
            })->whereHas('Region', function ($q) {
                $q->whereIn("Region.idRegion_Refugio", [3, 4, 5, 6, 7, 101])->orderBy("Region.Region", "asc");
            });
            /*
          where("idRefugio","=",1);*/
            /*	 return $query->where('votes', '>', 100);
            return  $this->Informacionadicional()->where("Informacion_Adicional_Refugio.Estado_Refugio","=","Activo");*/
            /* ->whereIn("Region.idRegion_Refugio",[3,4,5,6,7])->orderBy("Region.Region","asc")*/
        }
        public function nombresinacento()
        {
            return $this->sanear_string($this->Nombre);
        }
        public function totalmujeresano($ano, $id)
        {
            $nombre = $this->Nombre();
            $arreglo = DB::select(DB::raw("SELECT (SELECT count(Datos_Personales_Refugiada.Atiende) FROM   Datos_Personales_Refugiada, Refugio,Refugio_Region   WHERE Refugio.idRefugio=Refugio_Region.idRefugio  and  Datos_Personales_Refugiada.idRefugio=Refugio.idRefugio  and YEAR(Datos_Personales_Refugiada.Fecha_Ingreso)='$ano'and  Refugio_Region.idRegion='$id') 'Total'
		   	FROM
			Refugio
			WHERE
			Refugio.Nombre='$nombre'"));
            return $arreglo;
        }
        public function Totalmujeres()
        {
            $year = Carbon::now()->year;
            $id = $this->idregion();
            /*return $id;*/
            $nombre = $this->Nombre();
            $arreglogeneral = array();
            for ($i = 2010; $i <= $year; $i++) {
                $arreglo = DB::select(DB::raw("SELECT
		   	(SELECT count(Datos_Personales_Refugiada.Atiende) FROM   Datos_Personales_Refugiada, Refugio,Refugio_Region   WHERE Refugio.idRefugio=Refugio_Region.idRefugio  and  Datos_Personales_Refugiada.idRefugio=Refugio.idRefugio  and YEAR(Datos_Personales_Refugiada.Fecha_Ingreso)='$i'and  Refugio_Region.idRegion='$id') 'Total'
		   	FROM
			Refugio
			WHERE
			Refugio.Nombre='$nombre'"));
                /*return $arreglo[0]->Total;*/
                /*$arreglogeneral = a($arreglogeneral, 'Ano', $arreglo);*/
                $arreglogeneral[] = array('Ano' => $i, 'Total' => $arreglo[0]->Total);
            }
            return $arreglogeneral;
        }
        public function TotalmujeresCaer()
        {
            $year = Carbon::now()->year;
            $id = $this->idregion();
            /*return $id;*/
            $nombre = $this->Nombre();
            $arreglogeneral = array();
            for ($i = 2010; $i <= $year; $i++) {
                $arreglo = DB::select(DB::raw("SELECT
		   	(SELECT count(Expediente_Caer.Atiende) FROM CAER, Expediente_Caer, Refugio, Refugio_Region WHERE Expediente_Caer.caer_id = CAER.id AND CAER.Refugio_id = Refugio.idRefugio AND Refugio.idRefugio = Refugio_Region.idRefugio AND YEAR(Expediente_Caer.fecha_ingreso)='$i'and  Refugio_Region.idRegion='$id') 'Total'
		   	FROM
			Refugio
			WHERE
			Refugio.Nombre='$nombre'"));
                /*return $arreglo[0]->Total;*/
                /*$arreglogeneral = a($arreglogeneral, 'Ano', $arreglo);*/
                $arreglogeneral[] = array('Ano' => $i, 'Total' => $arreglo[0]->Total);
            }
            return $arreglogeneral;
        }
        public function logo()
        {
            if (file_exists(storage_path() . '/ImagenesRefugio/' . $this->idRefugio . '/logo.png')) {
                $img = Image::make(storage_path() . '/ImagenesRefugio/' . $this->idRefugio . '/logo.png')->resize(50, 50)->encode('data-url');
            } else {
                $img = Image::make(storage_path() . '/ImagenesRefugio/default/logo.png')->resize(50, 50)->encode('data-url');
            }
            return $img;
        }
        public static function sanear_string($string)
        {
            $string = trim($string);
            $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
                array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
                $string
            );
            $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
                array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
                $string
            );
            $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
                array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
                $string
            );
            $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
                array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
                $string
            );
            $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $string
            );
            $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'),
                array('n', 'N', 'c', 'C'),
                $string
            );
            //Esta parte se encarga de eliminar cualquier caracter extraño
            $string = str_replace(
                array("\\", "¨", "º", "-", "~",
                    "#", "@", "|", "!", "\"",
                    "·", "$", "%", "&", "/",
					"(", ")", "?", "'", "¡",
					"¿", "[", "^", "`", "]",
					"+", "}", "{", "¨", "´",
					">", "< ", ";", ",", ":",
					".", " "),
				'',
				$string
			);
			return $string;
		}
		public static function RefugiosActivosDesactivos($ano, $status = 1)
		{
			return DB::select(DB::raw("
				select R.idRefugio,R.Nombre, ifnull(R2.status,0) as status, R2.ano from Refugio as R left join(
				select Refugio.Nombre,Refugio_Activo.ano,Refugio_Activo.status,  Refugio_Activo.Refugio_id from  Refugio
				left join  Refugio_Activo  on  Refugio.idRefugio=Refugio_Activo.Refugio_id
				where ano=$ano and status=$status) as R2  on R.idRefugio=R2.Refugio_id
				where ifnull(status,0)=0"));
		}
//		-----------------------------------------------------------------------------------------------------------------------
        public function enviarReporte(){
            $correo=new ReporteMesMailable($this);
            Mail::to("ramiro174@gmail.com")->send($correo);
            Mail::to("rnrsiilge@gmail.com")->send($correo);
        }

    }
