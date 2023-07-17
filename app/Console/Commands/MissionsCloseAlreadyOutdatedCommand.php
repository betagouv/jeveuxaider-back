<?php

namespace App\Console\Commands;

use App\Jobs\MissionCloseAlreadyOutdatedJob;
use App\Models\Mission;
use App\Models\Participation;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class MissionsCloseAlreadyOutdatedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:close-already-outdated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One shot, do not execute more than once. Close missions and send testimony notifications.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $queryMission = Mission::with(['responsable'])->whereIn('id', $this->getmissionIds())
            ->where('state', 'Validée')
            ->where('end_date', '<', Carbon::now());
        $missionIds = $queryMission->pluck('id');

        $queryParticipationsBeingRefused = Participation::whereIn('mission_id', $missionIds)
            ->whereIn('state', ['En attente de validation', 'En cours de traitement']);

        $queryParticipationsValidatedFromMissionsOutdatedForLessThan6Months = Participation::whereIn('mission_id', $missionIds)
            ->where('state', 'Validée')
            ->whereHas('mission', function (Builder $query) {
                $query->where('end_date', '>', Carbon::now()->subMonth(6));
            });

        $this->info("{$missionIds->count()} missions vont être terminées.");
        $this->info("{$queryParticipationsBeingRefused->count()} participations en attente et en cours de traitement seront refusées (sans envoi de notification).");
        $this->info("{$queryParticipationsValidatedFromMissionsOutdatedForLessThan6Months->count()} notifications de témoignage seront envoyées.");

        if ($this->confirm("Continuer ?")) {
            $missionIds->each(fn ($id) => MissionCloseAlreadyOutdatedJob::dispatch($id));
        }
    }

    private function getmissionIds()
    {
        return [
            30822, 33761, 32984, 33022, 31011, 33622, 33995, 9907, 32383, 31052, 33994, 33784, 21534, 19931, 33026, 32392, 21543, 32380, 21536, 19897, 34151, 8856, 33603, 28696, 21538, 34627, 30919, 30005, 34479, 32264, 33322, 29890, 34538, 34154, 21376, 21043, 34148, 22166, 30564, 34058, 22018, 33235, 21952, 22019, 34059, 28710, 22012, 21961, 34303, 76, 30607, 29980, 33190, 29374, 26049, 31345, 33267, 22093, 33205, 32753, 21695, 21454, 31374, 33153, 21613, 27356, 23288, 9774, 22729, 32605, 32748, 22720, 21502, 20620, 21503, 8734, 29678, 33019, 34381, 3317, 32911, 31714, 31051, 34200, 32905, 30666, 30753, 33936, 33702, 33155, 33970, 32635, 32186, 25273, 33172, 34014, 19899, 19898, 32107, 33523, 33446, 33996, 30184, 33938, 31078, 32737, 33841, 34004, 31696, 33569, 22546, 30183, 33758, 33558, 29781, 33769, 30972, 33092, 20952, 29884, 32215, 23047, 32849, 32091, 20601, 20602, 32781, 19758, 28188, 33182, 31903, 28507, 32707, 33271, 33266, 33665, 31887, 31860, 32754, 33290, 32885, 32353, 33326, 33780, 33238, 33581, 20812, 20801, 20800, 29627, 28652, 26686, 33570, 20682, 30708, 26420, 33408, 31449, 33576, 33249, 31121, 30581, 28853, 20914, 33234, 20440, 33293, 31075, 30202, 33305, 20461, 31332, 8870, 33152, 32899, 33097, 32579, 20347, 19449, 31448, 30248, 33367, 29952, 28214, 33294, 30258, 32789, 29711, 32054, 30529, 19836, 18509, 32775, 32819, 33046, 32254, 32757, 33045, 32403, 30949, 32818, 32072, 32458, 27845, 32178, 32101, 19266, 5945, 19802, 20009, 20006, 21125, 30869, 30470, 27362, 20779, 22722, 27771, 32148, 32009, 24091, 29748, 23880, 22355, 28893, 33083, 30238, 24280, 23591, 20780, 29749, 29169, 23879, 29516, 30012, 24786, 20118, 20117, 20115, 20114, 31503, 32699, 32249, 32597, 31504, 32292, 30249, 31926, 31530, 31346, 31349, 31957, 32473, 31347, 19830, 32055, 32142, 32891, 31268, 32164, 25153, 31459, 31471, 31447, 25348, 19982, 19963, 32227, 32581, 32092, 29159, 29887, 31997, 32490, 31412, 32049, 32121, 32477, 32120, 32313, 32207, 31264, 31994, 19723, 32289, 28126, 30424, 28939, 30667, 31073, 32237, 31023, 31252, 31446, 19607, 30665, 29508, 29510, 28763, 31753, 31913, 31239, 32310, 32311, 12074, 19550, 32058, 32124, 31475, 31027, 30920, 32234, 31318, 32312, 19484, 32190, 31622, 31890, 30266, 31917, 32159, 32385, 19457, 31647, 31468, 28656, 31804, 32104, 25496, 29936, 30804, 30803, 31123, 30319, 25208, 32012, 31529, 31317, 30802, 30403, 28452, 30837, 32061, 32062, 31936, 31749, 29857, 30605, 30800, 30799, 13484, 31935, 32071, 29801, 31841, 31934, 32059, 30175, 30201, 17612, 31445, 31665, 18246, 31403, 13678, 31631, 18690, 27961, 20883, 6427, 18100, 8334, 21785, 20126, 28916, 16439, 28543, 30131, 29568, 31316, 30544, 31636, 31315, 31817, 31314, 31312, 29809, 31323, 25271, 31421, 31313, 30396, 30836, 19966, 31557, 31161, 31158, 31308, 31306, 31236, 31307, 31305, 31303, 31302, 31301, 31523, 30668, 31026, 31304, 29144, 31473, 31279, 30877, 28483, 31146, 29100, 31300, 31299, 29954, 31172, 31024, 28246, 31298, 31005, 30034, 30827, 19581, 18884, 29756, 31149, 25724, 18339, 29885, 18255, 30842, 30145, 30353, 30300, 30610, 30352, 20003, 30649, 30476, 30283, 18137, 30279, 30495, 18036, 18015, 18026, 18034, 18019, 18032, 18338, 26387, 19874, 29563, 29759, 26225, 20283, 22625, 26514, 25476, 22344, 22624, 27885, 30522, 26529, 29062, 28748, 29750, 29607, 27937, 29269, 30092, 29091, 24049, 30121, 29935, 30146, 32136, 28690, 29117, 29180, 30343, 30281, 28470, 9519, 19895, 28688, 26335, 29090, 29854, 29226, 29620, 29831, 21241, 26257, 30520, 23592, 30409, 28814, 30144, 28240, 29147, 30098, 30143, 29614, 29836, 30142, 30166, 27879, 28653, 28125, 29313, 29089, 27329, 29942, 29686, 30140, 30141, 29070, 24880, 29099, 29839, 28361, 29292, 30139, 29069, 30138, 29088, 29576, 29652, 30036, 28904, 23355, 13693, 13692, 28625, 29322, 27820, 29087, 11598, 11596, 19876, 28745, 29534, 27930, 17215, 6827, 13899, 27803, 13900, 27799, 13894, 24944, 27798, 22295, 27802, 29048, 13897, 29336, 27793, 23755, 27800, 27795, 27797, 27801, 22913, 27808, 13818, 25289, 23939, 25288, 22891, 22889, 13892, 22890, 13902, 13901, 14382, 22297, 20075, 13903, 27806, 27805, 27804, 19886, 17441, 18686, 17079, 14218, 15518, 9310, 19827, 9666, 16396, 9337, 19295, 12775, 10150, 14096, 7602, 9311, 16612, 17176, 17924, 15679, 18001, 17169, 13414, 8714, 12777, 22068, 16226, 19242, 19411, 17997, 10152, 17194, 14097, 10706, 18934, 19690, 19087, 8399, 19541, 12771, 7393, 20007, 20366, 19922, 19094, 18646, 20278, 19965, 20737, 19211, 22933, 19566, 8700, 26375, 19179, 24348, 20208, 23116, 22719, 24653, 22478, 8149, 21677, 22642, 9546, 29308, 21664, 27912, 19918, 22825, 24276, 21703, 5966, 18179, 24237, 12487, 23767, 24283, 26995, 23295, 14927, 23668, 20452, 27502, 21717, 22070, 22134, 21021, 23878, 23109, 14955, 22069, 23654, 23653, 22781, 24395, 24114, 24052, 27642, 18149, 22299, 21108, 24246, 22674, 21406, 22473, 20591, 25605, 22641, 25590, 14473, 29068, 27321, 23113, 8798, 25975, 25974, 20948, 28473, 20475, 25589, 22044, 20493, 24236, 21819, 25976, 22133, 22920, 22132, 20610, 26513, 21486, 23720, 18227, 24234, 23457, 25065, 24141, 12636, 21616, 25850, 22454, 26452, 23557, 23655, 21711, 23045, 23881, 23819, 18710, 16470, 18708, 23669, 21018, 23563, 29135, 29136, 21859, 29592, 28442, 29157, 26935, 26680, 15966, 22615, 23777, 27825, 27827, 29160, 28303, 28734, 28731, 28979, 28150, 28380, 28079, 27859, 28167, 28170, 28277, 27440, 29066, 26631, 27205, 27218, 29139, 24734, 27266, 27444, 27652, 28646, 18511, 27818, 29507, 24864, 28812, 26678, 20485, 29067, 19544, 19726, 28302, 25660, 20028, 27232, 29130, 28862, 21119, 28837, 26743, 27811, 29134, 17228, 27780, 22784, 28883, 26777, 21359, 27244, 10884, 29065, 29112, 28889, 28898, 26362, 26365, 29051, 27878, 28880, 28495, 28981, 25962, 23702, 26706, 28248, 24793, 23293, 20882, 21343, 10886, 28439, 29049, 12759, 21586, 28200, 24017, 28908, 28607, 28873, 24018, 27965, 26776, 27569, 28744, 27315, 27676, 10704, 28919, 28231, 28730, 25442, 28773, 28301, 15607, 28149, 29320, 28189, 28064, 28065, 27132, 26772, 28230, 28239, 28192, 26336, 28162, 28477, 26661, 27670, 15424, 15442, 27527, 26903, 24528, 27383, 28190, 22851, 23871, 23869, 27617, 26945, 24886, 23302, 23301, 24654, 26079, 27254, 18050, 25866, 26212, 22755, 27649, 24254, 26210, 27579, 26209, 26206, 26878, 26208, 28451, 27333, 28322, 27384, 28067, 27768, 27140, 27788, 27854, 26827, 27941, 26565, 27528, 27540, 6412, 28025, 28173, 26583, 28354, 26901, 28299, 27977, 28075, 27752, 28071, 26846, 28026, 27256, 28069, 27180, 22831, 22829, 22830, 22832, 27580, 20321, 22844, 21036, 28187, 26672, 19308, 26422, 27355, 21605, 21048, 27728, 33849, 27715, 27830, 27475, 20316, 20336, 27926, 27309, 12467, 27092, 26771, 26355, 26483, 24303, 25513, 17503, 17471, 20030, 19954, 18017, 26444, 13329, 18455, 20253, 26226, 25467, 21106, 15516, 13626, 17149, 16234, 15161, 15160, 17142, 16237, 18964, 17147, 9617, 16233, 17150, 9615, 17148, 19964, 23307, 17226, 23303, 26696, 23308, 23304, 23306, 23965, 23305, 23699, 26022, 18002, 8430, 8617, 8613, 9312, 26571, 26252, 25180, 25272, 27240, 26269, 26894, 25503, 25203, 14217, 26118, 13330, 26469, 16456, 19215, 26636, 24302, 25829, 22393, 22390, 18916, 25753, 22973, 25295, 23478, 25446, 26488, 20439, 26240, 26325, 24112, 21552, 25584, 25587, 26105, 26059, 20285, 20609, 23229, 18447, 13384, 13389, 4063, 13380, 13386, 13387, 13379, 15092, 13388, 13381, 13377, 20213, 22918, 23763, 23407, 22219, 23135, 24057, 22218, 22490, 23762, 23935, 24920, 21709, 22281, 24702, 20423, 22514, 21181, 20683, 21856, 24058, 25410, 22772, 21918, 22276, 19085, 24094, 21438, 22597, 23874, 19725, 22377, 13052, 13067, 13064, 13069, 13059, 13055, 13062, 13061, 13056, 13051, 13050, 13060, 13053, 13058, 13054, 13063, 13071, 19973, 23665, 24687, 12690, 24854, 25449, 20325, 18628, 24826, 24827, 24433, 24425, 21369, 24424, 21350, 24468, 24239, 12957, 24469, 20516, 24769, 25457, 22647, 8455, 21812, 21853, 24603, 24602, 24464, 11753, 24980, 21871, 13776, 17920, 18022, 12706, 18020, 22903, 22189, 25198, 12712, 12657, 12669, 12655, 12666, 12653, 12659, 12664, 12665, 12663, 12661, 12658, 12671, 12654, 12668, 12662, 12660, 12670, 12656, 24684, 22186, 16613, 22672, 21545, 9193, 21872, 20870, 23207, 18009, 22499, 24757, 9083, 24056, 21583, 23321, 23553, 19503, 12053, 19500, 5373, 12432, 8432, 12428, 19502, 19505, 10113, 19501, 18892, 18194, 13826, 9007, 6676, 6677, 17187, 14307, 14132, 9769, 9945, 20420, 12436, 11532, 14304, 14374, 16108, 12502, 18214, 14373, 12102, 12099, 19740, 16254, 12103, 19313, 15832, 17949, 13166, 12785, 14309, 14856, 14305, 14303, 12096, 12100, 12098, 17189, 17950, 12228, 12391, 12434, 19608, 19742, 17188, 12398, 9696, 12371, 14308, 13223, 12538, 18692, 13634, 14306, 13224, 12435, 12020, 18782, 15232, 23409, 22554, 23930, 22446, 21505, 21851, 21231, 22634, 20057, 23932, 23147, 22053, 21850, 15233, 20637, 21238, 21624, 21054, 23144, 22782, 21167, 21179, 20060, 21147, 20128, 21087, 21800, 19356, 21080, 22743, 21239, 21085, 22683, 20852, 20784, 18641, 20927, 22169, 17629, 22935, 23648, 22357, 24693, 18717, 21512, 21706, 24531, 22817, 17495, 17487, 22684, 16687, 23292, 20513, 23236, 20363, 12056, 12057, 12059, 12060, 12058, 21540, 21170, 10770, 17986, 11007, 19145, 12184, 13836, 11011, 23775, 22619, 20745, 19551, 12000, 12722, 12188, 11732, 5990, 19375, 17097, 23574, 23912, 20465, 20989, 11320, 15271, 16221, 17083, 11524, 14868, 19588, 20212, 21593, 21110, 17823, 12429, 12055, 18215, 17704, 17679, 19996, 22198, 23338, 22681, 22682, 19586, 11764, 21222, 21218, 21457, 21458, 19140, 20508, 23966, 21592, 21875, 17616, 17615, 22356, 12052, 12049, 14879, 20263, 14878, 9725, 6492, 20232, 20468, 19452, 22623, 11798, 11709, 12756, 19547, 22187, 21895, 22704, 11745, 7084, 21836, 21900, 11854, 14616, 22584, 5644, 16228, 20076, 14535, 17430, 18148, 20223, 17984, 21897, 13644, 12021, 21226, 13497, 21916, 20781, 21905, 12528, 21798, 22038, 23120, 19977, 11977, 13222, 15169, 16229, 11009, 17067, 15170, 17985, 9733, 5372, 19109, 12750, 9500, 8772, 12572, 13824, 15270, 15178, 13841, 20466, 22609, 20666, 21556, 21557, 21342, 18730, 13017, 13018, 14916, 18147, 18971, 12560, 17853, 19814, 9465, 17508, 12329, 14716, 12644, 14949, 12546, 12350, 13021, 5646, 17786, 11864, 1518, 12417, 14951, 12415, 19741, 12307, 12903, 14914, 18675, 12134, 14897, 18550, 13495, 18788, 13032, 5250, 14162, 15707, 15708, 18130, 13028, 10425, 12681, 18146, 11863, 5712, 14778, 12354, 11415, 19212, 11048, 18551, 11417, 12804, 17998, 11397, 16399, 17784, 12680, 12144, 9527, 7167, 9614, 5939, 19178, 666, 19177, 8061, 16385, 16384, 12140, 10557, 12351, 11314, 11660, 11866, 16831, 19743, 11068, 13219, 16223, 16688, 14597, 14533, 12754, 20025, 20728, 20997, 22066, 20428, 22469, 20970, 21960, 14829, 20668, 21784, 20879, 13197, 13015, 14074, 5654, 11618, 9545, 13523, 12510, 13407, 20678, 13698, 9381, 20166, 22849, 20789, 22179, 12418, 17925, 12008, 22530, 13471, 15198, 13043, 461, 12810, 18962, 17951, 20773, 20041, 22902, 11679, 16383, 13726, 16695, 18254, 22181, 21055, 22380, 22103, 20145, 15438, 20301, 13215, 22203, 12306, 13104, 11975, 21227, 11256, 21060, 21101, 9882, 12887, 21638, 20564, 20156, 22289, 14141, 13675, 14139, 14147, 14137, 14142, 14149, 14146, 14143, 14148, 14150, 19496, 12941, 12768, 11527, 14474, 19864, 21630, 17611, 21421, 21041, 21744, 19987, 20729, 20507, 12400, 18931, 20187, 19249, 19991, 13996, 19676, 19677, 14825, 20049, 20702, 21756, 21758, 18668, 1654, 20017, 20210, 20211, 20790, 20526, 18549, 20001, 17512, 9499, 20064, 20337, 19207, 6678, 6679, 16420, 9678, 9483, 9751, 20191, 20155, 20371, 9454, 17484, 17428, 17472, 17482, 19962, 20495, 10296, 19908, 20686, 16011, 16005, 16009, 3643, 8461, 9821, 9820, 9819, 9456, 20091, 18789, 20533, 20534, 16371, 18189, 19051, 19407, 16620, 9397, 19949, 8046, 14761, 6969, 8750, 11741, 19593, 9503, 14202, 19237, 18999, 19104, 19244, 19507, 16449, 13542, 19309, 18224, 19103, 18035, 17258, 8505, 8504, 8503, 8557, 17399, 14717, 8537, 14387, 17202, 14151, 8470, 15065, 18382, 15982, 8689, 8393, 9009, 8690, 8686, 8687, 8612, 8270, 8314, 8885, 8886, 8884, 8570, 8042, 8041, 17301, 16185, 15308, 13228, 14380, 8028, 16500, 16359, 7268, 12147, 6821, 7022, 6851, 11603, 7773, 11100, 9737, 8760, 6874, 9306, 12708, 8127, 7856, 6962, 6284, 6191, 7072, 7603, 7021, 14983, 6699, 8260, 9789, 7772, 7774, 7857, 9305, 7853, 9790, 7851, 12949, 9481, 12496, 7983, 11004, 12412, 16369, 16191, 9645, 14943, 15071, 8832, 7156, 14180, 8710, 14390, 9626, 11519, 14079, 14080, 14926, 10407, 14106, 7159, 12648, 7175, 12163, 7178, 6265, 14663, 12562, 14229, 8266, 7269, 14057, 6838, 6913, 6281, 6514, 8345, 9175, 8348, 9910, 8338, 11631, 8252, 7883, 11632, 6653, 8349, 11743, 11521, 6479, 6273, 12596, 6353, 7023, 6694, 12535, 11685, 9167, 7418, 5881, 9746, 8413, 6126, 8891, 9765, 7344, 9463, 10132, 8677, 7914, 10140, 12672, 12076, 4433, 5777, 12456, 11371, 10610, 12043, 9845, 11714, 9625, 8598, 10415, 8834, 9148, 8892, 5659, 11402, 9901, 11504, 6968, 7685, 6071, 9343, 6610, 7414, 8429, 7104, 11834, 8602, 8603, 8604, 6967, 8606, 11859, 8607, 8609, 8610, 11506, 8611, 8487, 4569, 9259, 8608, 6282, 7635, 9248, 8593, 9344, 9319, 9272, 7742, 8601, 8843, 509, 8561, 8599, 9256, 8597, 8596, 8592, 8594, 9250, 9267, 9257, 9260, 9258, 9274, 9266, 9264, 9261, 9265, 11529, 5264, 11867, 9904, 9677, 5882, 6601, 10680, 8249, 5567, 8037, 107, 8871, 7781, 8539, 8417, 9180, 6793, 7895, 11172, 5317, 9756, 9634, 5895, 11661, 11372, 9501, 9403, 5166, 8996, 10093, 9295, 8355, 5951, 7576, 8088, 8802, 5177, 8852, 9720, 11015, 7329, 7331, 10495, 7332, 7330, 9892, 9340, 8560, 5197, 6179, 5652, 5655, 8379, 5650, 7723, 5649, 5374, 10511, 6952, 7692, 7674, 6177, 8489, 8378, 8931, 5110, 8220, 8261, 8993, 7675, 5319, 9399, 6656, 9138, 6868, 9349, 6713, 9235, 6184, 8995, 6521, 10065, 9326, 6091, 6787, 8050, 6657, 5996, 4866, 7807, 9184, 8823, 7061, 8835, 8467, 6441, 8727, 7767, 9169, 9922, 6780, 7364, 6491, 7282, 8830, 8086, 8403, 6847, 8027, 8468, 7066, 9236, 5168, 9131, 8383, 5579, 6379, 11296, 9768, 9767, 9496, 4185, 7385, 8535, 5253, 9153, 7677, 9285, 5187, 8295, 5751, 5761, 9938, 5950, 8420, 10907, 10714, 8724, 10058, 6773, 9263, 5155, 9262, 9269, 9273, 9253, 9252, 9270, 9275, 9271, 9268, 9451, 5111, 4871, 4945, 4869, 8406, 9132, 7181, 9079, 5172, 5052, 2751, 5997, 7601, 5304, 4320, 3081, 8279, 5957, 8473, 7629, 8460, 9660, 5185, 9719, 4943, 10068, 8736, 9415, 9196, 5112, 9413, 8861, 9721, 9192, 1093, 4855, 4714, 7664, 6341, 3430, 7864, 9171, 7079, 7310, 3137, 1635, 8934, 8069, 885, 4907, 8935, 9724, 9380, 9223, 9433, 9449, 6178, 6116, 1551, 453, 9379, 9333, 9313, 4921, 3221, 3052, 2173, 7915, 7916, 8463, 7643, 6244, 8451, 6864, 5251, 7660, 3614, 6943, 5965, 8502, 8699, 7100, 7611, 2084, 401, 7985, 8661, 8644, 1402, 8887, 8474, 2305, 8641, 8553, 8552, 8551, 8663, 7891, 7665, 7383, 7355, 7913, 7695, 7594, 6891, 7663, 7698, 7027, 7680, 8120, 8119, 7420, 7424, 7127, 6826, 7036, 7133, 8170, 7822, 8225, 7791, 7733, 7683, 7593, 7752, 7799, 6160, 7798, 7771, 7419, 7076, 7681, 7352, 6567, 6566, 7510, 7745, 7592, 7881, 7125, 7734, 7732, 7591, 7831, 6788, 6887, 8219, 8218, 6852, 7824, 7557, 7797, 7894, 7751, 7924, 7729, 6719, 7788, 7101, 7861, 7106, 6612, 7648, 7794, 7912, 7811, 7391, 7801, 7790, 7911, 7862, 7910, 7844, 6608, 6047, 6611, 6528, 7909, 6585, 6584, 4857, 4188, 3920, 5107, 1656, 195, 7003, 6763, 6009, 6010, 7829, 7372, 6625, 7783, 1636, 178, 6529, 4216, 6910, 7147, 6762, 1629, 3949, 1240, 1947, 6609, 3449, 6400, 7336, 6876, 6450, 4858, 1282, 6358, 7281, 1433, 6627, 7655, 6636, 7571, 7626, 7358, 5254, 6873, 6777, 4186, 7444, 6761, 7390, 6050, 7064, 7884, 7792, 6958, 7793, 6941, 6959, 7354, 7569, 3292, 7315, 7357, 6778, 7640, 6183, 5991, 7032, 6872, 7290, 7356, 7304, 7632, 6997, 6003, 6792, 6616, 6815, 7518, 7370, 6862, 6804, 7115, 6875, 6729, 6730, 6519, 7094, 6765, 6965, 6589, 6588, 7114, 6525, 6752, 7113, 6816, 6526, 6768, 7010, 6743, 6732, 6530, 7099, 7160, 6691, 6606, 6587, 6504, 6559, 6558, 6802, 4928, 6571, 6820, 6560, 6527, 4420, 6745, 6276, 6347, 6786, 7508, 7088, 6853, 6981, 7338, 6414, 7180, 6757, 7179, 6960, 7068, 6791, 6950, 6508, 6440, 6523, 7513, 6524, 6880, 6070, 6764, 7093, 6866, 7387, 7320, 7091, 7090, 6517, 7012, 6655, 5913, 6495, 5915, 6546, 6125, 3880, 6092, 5312, 2947, 3476, 5311, 5310, 3993, 3603, 3602, 4069, 4905, 4094, 8475,
        ];
    }
}