-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Dim 21 Avril 2013 à 11:59
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `duckalendar`
--

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci NOT NULL,
  `beginTime` time NOT NULL,
  `endTime` time NOT NULL,
  `endDate` date NOT NULL,
  PRIMARY KEY (`login`,`date`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `events`
--

INSERT INTO `events` (`login`, `date`, `name`, `desc`, `beginTime`, `endTime`, `endDate`) VALUES
('Natsirtt', '1991-12-19', 'EventName', 'Un super super évènement !', '12:34:00', '16:12:00', '1991-12-23'),
('Natsirtt', '2012-12-12', 'EvenementTest', 'Un Ã©vÃ¨nement pour tester !', '17:35:00', '16:09:00', '2012-12-15');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`login`, `password`, `salt`, `ip`) VALUES
('a', '?\\zYc6LM5dAG6', '?\\.{w}!,&![2pgnp;8"fuz0p2:afc!#%', '127.0.0.1'),
('aa', '/5L0cR/wpFqSA', '/5d?[;&e!{6ma=ee_2;%]dt/h(4,fft=', '127.0.0.1'),
('aaa', ';rvsokEWN7qTE', ';ru5_l8''?%syx#v}\\b[+}2dw7v=f?]k1', '127.0.0.1'),
('Admin', 'qdt9vfDoQO2pk', 'qdc?=l;"_sy5q_0j:eybci}wn3.k=8gs', '127.0.0.1'),
('dscdsv', '9?gzzIpxNepxM', '9?b6c/9upul02!o#n/))f3eodw:r4/+1', '127.0.0.1'),
('dzakkd', '[vyrhLkZDnnxY', '[vk57f/zffvd&x{7+9''le?)"x2:o!z.3', '127.0.0.1'),
('e', '.=U/KBgTSTelc', '.=z#5:mur8+w}b;%!5:b4?u9=&4y%mjd', '127.0.0.1'),
('ejopj', 'd_QcvEr1PRPSM', 'd__g!.k0)5p)09u\\wou_tvs#f.zg2pk#', '127.0.0.1'),
('ij', ')4aZ4sAqg/BSM', ')45=e!14pd0}4=.7i#pa;0l5+l%\\jvyg', '127.0.0.1'),
('ijp', 'u=GdyZA2EBdWk', 'u=gpd+q4p+5sr}0qw:''[1go;t)kjr9d_', '127.0.0.1'),
('ijpij', 'j&DV7LmqrN0L6', 'j&p%cot4bafbwb]f71op;#m_&9[5&0}z', '127.0.0.1'),
('jÃ´vez', 'u5ozBcQVjmx3Y', 'u5%lbim5[;c23]1}_)=nl4i''0"po=z9l', '127.0.0.1'),
('jj', '/jNSbCQVj1eiM', '/j/xdf%28_+2co7gzfp=l]=(1h;57ewb', '127.0.0.1'),
('jvoe^zjezov', ',xz6DY2GqQPP.', ',xb[u,6!o][db\\c6&///dzf3#w:mgk=.', '127.0.0.1'),
('k', '2yaRBjJqCJHKI', '2y,,rb15?/_k?xzz=0[n;8hx;41=#{az', '127.0.0.1'),
('kjok', 'h#TYl3Gtmu8BQ', 'h#o:oj_&r{(71ek;;x7+xl81p''j!f6tt', '127.0.0.1'),
('LA REPUBLIQUE', '?3X1J8G1nHLQQ', '?3/(6#9?x,dpz6d(illvn#[)lr[m2cb6', '127.0.0.1'),
('Login', '3by9gChRc9Le6', '3b\\w:m%t4&7\\1:j}g/10=vh]".48e0?(', '127.0.0.1'),
('loginds', '.mC7LOYOiR7uQ', '.my''us}x''.a08tr5n?r}pg#!hsy=hyd"', '127.0.0.1'),
('logine', 'k#2o7hoD.pkjk', 'k#khb5g&9)&59mazg:,;\\;"x04''3yy]k', '127.0.0.1'),
('loginejj', '1sh3MD6zSSl9w', '1s]u33t%okhszo0{v!hr93,6g(5c9,#=', '127.0.0.1'),
('loginejz', '%7/b9cx2mGCfQ', '%7:7it\\1kv''u''\\z%2bl5f3x;{j2me#c9', '127.0.0.1'),
('loginez', '6.EzzMI2mNQjI', '6.swj\\1e}tt\\rss%,7/kq''j:f11"ckd6', '127.0.0.1'),
('loginj', '3d.3IJOVBaz6g', '3d;qlf.#8%u_43t#z"?slt/5tr''zks/p', '127.0.0.1'),
('loginjezo', 'v"jc0QmiVWFaA', 'v"rk(_y%ov/gb+dt}(8fqx:56p2";[)n', '127.0.0.1'),
('loginjko', 'w3cQWqXACo/1A', 'w3){y!i[]x66db?c#c8drg5z''p#"[85''', '127.0.0.1'),
('loginjzecjjpzejcpje', '2}ewVEw7R2GAw', '2}c8gg]1{vu9{b0q}.;&t]%=8]do:jt6', '127.0.0.1'),
('loginkpzsk', '}y4sZUszNap06', '}yeo8k0_j[o8l(ccw!g68[d0p"mduqtk', '127.0.0.1'),
('loginoojp', '"nJmAkNcT72u6', '"n0z+57fqy]5"=9?(rp=!ge=?!/%h2(]', '127.0.0.1'),
('loginop', 'j2mUI5qvBzpIU', 'j2{#qve#5xkb%4r''4bzpl''+xjge]6nec', '127.0.0.1'),
('loginpj', ')0r5Ugf74AIGM', ')02;4bbz0tdhvvx1!(%#}#!#2tc_ygb)', '127.0.0.1'),
('loginpzepv', 'xsFjhU22u8Fuo', 'xs]q.5czg!e%+m4878jbk;v2iw!o%ob2', '127.0.0.1'),
('loginpzjjcj', 'dlKRxT2FoZOYA', 'dlr5(=h{;"8n2tu+ky{nh!!g{tw/t?mw', '127.0.0.1'),
('loginsdv', '!qmRGy0MczwnE', '!q{={{ol:7\\3:hv)"j4t3.&zpid];}5#', '127.0.0.1'),
('loginsdvdsv', '?r9no9S6oMnf.', '?rtl#(wr(&''d!]o61]]!1%azsfnt(c{2', '127.0.0.1'),
('loginv', 'pt/P.3xjlaU1g', 'ptt]/#fzl{si.{''?fx1gdw:{7cm"zh3l', '127.0.0.1'),
('loginvjeo', '!oJZs3XczMf.c', '!o8(5/{jec)ltk''lh.pw;wlk9:cs5=\\_', '127.0.0.1'),
('loginvsd', ';tM5S0EIZJKHI', ';t}d/t%6}xvcl;(oq(;nw5k%%/fd+u''%', '127.0.0.1'),
('loginze', '4)jXe011Rb9po', '4)md9{=\\4gwsv_h\\m=7&[80y%:g.61a[', '127.0.0.1'),
('loginzj', '9_f.4CjzJgPqQ', '9_f"n=bq:9f0n(:=,sd4nu!2y(r?bj}+', '127.0.0.1'),
('loginzokcz', '7o0ppLxgDVmGc', '7o[ivw.#&;ld\\4&}i6wzvw4&(8e+91xz', '127.0.0.1'),
('loginzopj', 'i&1LYO/kerx8I', 'i&rd+''8pzxzmvv}j#w/zat[jc4&.tum8', '127.0.0.1'),
('Lolipop    ', 'tyHjbJpR.L7T2', 'typ4fc/p=32&+07ctvmo=ig6/7uqu,#!', '127.0.0.1'),
('lpl', '#&pedtE/rlQa2', '#&r)7o}''}.0.]ihqxl!(2jdwp1:8"/c#', '127.0.0.1'),
('Nat', 'xzFMajiT2u/cQ', 'xzdlx!/1z6\\l&8kz3u%.](9glnx7''d,?', '127.0.0.1'),
('Natsirtt', 't&gEPp/ckYGmo', 't&5:t&v5,&8c8/e("a.[{r2,zus3!b9w', '127.0.0.1'),
('Natsirtt2', '(?ZrmS61f3/4Y', '(?]}gdx,}b+z{,5cmb,j\\5\\t{[iva,ef', '127.0.0.1'),
('Natsirttazd', '1#nXSiWD1qn1A', '1#x4zh?.\\b?fuks#nn?z\\6&]''%6,b!,)', '127.0.0.1'),
('njn', '5]ABS/.XryhAk', '5]st''ko+dsp};4%kb=60yn!}fp?]?/x(', '127.0.0.1'),
('p', 'jwSamN1gljMBA', 'jwrhw&%v\\w"_sgwrytrn[qz!&{w4u):q', '127.0.0.1'),
('pji', '[''05n2JEUfXYI', '[''oscijb73q6%=?ch/_qrh_c_r}dqxr0', '127.0.0.1'),
('po', 'jobbj4Fd7EAng', 'jo4rszxw_2}0cw{];d;i\\)&ue6py,}sw', '127.0.0.1'),
('pzejpo', 'bmwh2k./spDdY', 'bm;l_z6)2m=qc=l5v3%{m799[st(%_i,', '127.0.0.1'),
('pzek', 'o2QuQa4nMqcLM', 'o27rrox6[_=_4xj"g(7t6:g)nx:;tf2;', '127.0.0.1'),
('Rufymaze', 'j5wqleKcGtuqE', 'j54/4}5zaf7;;k]&vbko\\1%n{ek%]"!i', '127.0.0.1'),
('sdvcds', '''\\OEJJ5J31dmI', '''\\8}#(9xk}_c;w6]\\_mz494"rqxbmhj[', '127.0.0.1'),
('V', ';8JRvd/sVtej2', ';8c+y[ccwsp(g24:yy0''''5}b7(1d8ja:', '127.0.0.1'),
('vjnkjnnn', 'l(l/tojKrjHbY', 'l(}4]b&8elm!&zp(b09,\\nz:x"wy_3;o', '127.0.0.1'),
('vv', 'r0/OR67FPw/Pw', 'r0/,hg)(p\\{g/02]ybg!9lm9kd6!e.}[', '127.0.0.1'),
('z', '34LjcDtcW0s0o', '34b%v5?w''y[h=8w''2a1()?#bw585.#,0', '127.0.0.1'),
('zejopc', ';uZF59dj8rHok', ';uegrjih0.[+)ywbyx}?,(;[{f?9[r05', '127.0.0.1'),
('zjepojc', '7aON9cCGKFFi.', '7aak{}hqo(}vfz4sk;k=;#+:4xz6sb}d', '127.0.0.1'),
('zkz', 'pyf3bPn8m7z7Y', 'pyx[3#0m&:qwj{79b#j8c,v;{z''v?#a}', '127.0.0.1'),
('zo', 'o_SfrtaaE.7KI', 'o_=7s./5z%l,8=p:g++d+7+}!0lh0{/p', '127.0.0.1'),
('zopj', '4a3gehcFpLH5Y', '4a5i(fi8{d;8u)&vq#}=_g2]dj61%4r7', '127.0.0.1');
