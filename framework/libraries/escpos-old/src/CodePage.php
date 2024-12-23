<?php
abstract class CodePage {
	/** Code page constants, exported from iconv -l. Can be cut down*/
	const CP037 = "CP037";
	const CP038 = "CP038";
	const CP273 = "CP273";
	const CP274 = "CP274";
	const CP275 = "CP275";
	const CP278 = "CP278";
	const CP280 = "CP280";
	const CP281 = "CP281";
	const CP282 = "CP282";
	const CP284 = "CP284";
	const CP285 = "CP285";
	const CP290 = "CP290";
	const CP297 = "CP297";
	const CP367 = "CP367";
	const CP420 = "CP420";
	const CP423 = "CP423";
	const CP424 = "CP424";
	const CP437 = "CP437";
	const CP500 = "CP500";
	const CP737 = "CP737";
	const CP770 = "CP770";
	const CP771 = "CP771";
	const CP772 = "CP772";
	const CP773 = "CP773";
	const CP774 = "CP774";
	const CP775 = "CP775";
	const CP803 = "CP803";
	const CP813 = "CP813";
	const CP819 = "CP819";
	const CP850 = "CP850";
	const CP851 = "CP851";
	const CP852 = "CP852";
	const CP855 = "CP855";
	const CP856 = "CP856";
	const CP857 = "CP857";
	const CP860 = "CP860";
	const CP861 = "CP861";
	const CP862 = "CP862";
	const CP863 = "CP863";
	const CP864 = "CP864";
	const CP865 = "CP865";
	const CP866 = "CP866";
	const CP866NAV = "CP866NAV";
	const CP868 = "CP868";
	const CP869 = "CP869";
	const CP870 = "CP870";
	const CP871 = "CP871";
	const CP874 = "CP874";
	const CP875 = "CP875";
	const CP880 = "CP880";
	const CP891 = "CP891";
	const CP901 = "CP901";
	const CP902 = "CP902";
	const CP903 = "CP903";
	const CP904 = "CP904";
	const CP905 = "CP905";
	const CP912 = "CP912";
	const CP915 = "CP915";
	const CP916 = "CP916";
	const CP918 = "CP918";
	const CP920 = "CP920";
	const CP921 = "CP921";
	const CP922 = "CP922";
	const CP930 = "CP930";
	const CP932 = "CP932";//
	const CP933 = "CP933";
	const CP935 = "CP935";
	const CP936 = "CP936";
	const CP937 = "CP937";
	const CP939 = "CP939";
	const CP949 = "CP949";
	const CP950 = "CP950";
	const CP1004 = "CP1004";
	const CP1008 = "CP1008";
	const CP1025 = "CP1025";
	const CP1026 = "CP1026";
	const CP1046 = "CP1046";
	const CP1047 = "CP1047";
	const CP1070 = "CP1070";
	const CP1079 = "CP1079";
	const CP1081 = "CP1081";
	const CP1084 = "CP1084";
	const CP1089 = "CP1089";
	const CP1097 = "CP1097";
	const CP1112 = "CP1112";
	const CP1122 = "CP1122";
	const CP1123 = "CP1123";
	const CP1124 = "CP1124";
	const CP1125 = "CP1125";
	const CP1129 = "CP1129";
	const CP1130 = "CP1130";
	const CP1132 = "CP1132";
	const CP1133 = "CP1133";
	const CP1137 = "CP1137";
	const CP1140 = "CP1140";
	const CP1141 = "CP1141";
	const CP1142 = "CP1142";
	const CP1143 = "CP1143";
	const CP1144 = "CP1144";
	const CP1145 = "CP1145";
	const CP1146 = "CP1146";
	const CP1147 = "CP1147";
	const CP1148 = "CP1148";
	const CP1149 = "CP1149";
	const CP1153 = "CP1153";
	const CP1154 = "CP1154";
	const CP1155 = "CP1155";
	const CP1156 = "CP1156";
	const CP1157 = "CP1157";
	const CP1158 = "CP1158";
	const CP1160 = "CP1160";
	const CP1161 = "CP1161";
	const CP1162 = "CP1162";
	const CP1163 = "CP1163";
	const CP1164 = "CP1164";
	const CP1166 = "CP1166";
	const CP1167 = "CP1167";
	const CP1250 = "CP1250";
	const CP1251 = "CP1251";
	const CP1252 = "CP1252";
	const CP1253 = "CP1253";
	const CP1254 = "CP1254";
	const CP1255 = "CP1255";
	const CP1256 = "CP1256";
	const CP1257 = "CP1257";
	const CP1258 = "CP1258";
	const CP1282 = "CP1282";
	const CP1361 = "CP1361";
	const CP1364 = "CP1364";
	const CP1371 = "CP1371";
	const CP1388 = "CP1388";
	const CP1390 = "CP1390";
	const CP1399 = "CP1399";
	const CP4517 = "CP4517";
	const CP4899 = "CP4899";
	const CP4909 = "CP4909";
	const CP4971 = "CP4971";
	const CP5347 = "CP5347";
	const CP9030 = "CP9030";
	const CP9066 = "CP9066";
	const CP9448 = "CP9448";
	const CP10007 = "CP10007";
	const CP12712 = "CP12712";
	const CP16804 = "CP16804";
	const ISO8859_7 = "ISO_8859-7";
	const ISO8859_2 = "ISO_8859-2";
	const ISO8859_15 = "ISO_8859-15";
	const RK1048 = "RK1048";
	// Code pages which are not built in
	// to default iconv on Debian.
	const CP720 = false;
	const CP853 = false;
	const CP858 = false;
	const CP928 = false;
	const CP1098 = false;
	const CP747 = false;
	
	/*
	 * Below code pages appear to be vendor-specific (Star), so iconv wont use them
	 * They are being merged gradually into the StarCapabilityProfile.
	 */
	const CP3840 = false;
	const CP3841 = false;
	const CP3843 = false;
	const CP3844 = false;
	const CP3845 = false;
	const CP3847 = false;
	const CP3846 = false;
	const CP3848 = false;
	const CP1001 = false;
	const CP2001 = false;	
	const CP3001 = false;
	const CP3002 = false;
	const CP3011 = false;
	const CP3012 = false;
	const CP3021 = false;
	const CP3041 = false;
}