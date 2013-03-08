--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.4
-- Dumped by pg_dump version 9.2.0
-- Started on 2013-02-27 14:48:59 EET

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 191 (class 3079 OID 11907)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2304 (class 0 OID 0)
-- Dependencies: 191
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 161 (class 1259 OID 122881)
-- Name: action_comments; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE action_comments (
    id integer NOT NULL,
    action_id integer NOT NULL,
    user_id integer NOT NULL,
    comment text NOT NULL,
    created timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 162 (class 1259 OID 122888)
-- Name: action_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2305 (class 0 OID 0)
-- Dependencies: 162
-- Name: action_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_comments_id_seq OWNED BY action_comments.id;


--
-- TOC entry 2306 (class 0 OID 0)
-- Dependencies: 162
-- Name: action_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_comments_id_seq', 4, true);


--
-- TOC entry 190 (class 1259 OID 123131)
-- Name: action_email_templates; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE action_email_templates (
    id integer NOT NULL,
    action_type_id integer NOT NULL,
    subject character varying(255) NOT NULL,
    content text NOT NULL
);


--
-- TOC entry 189 (class 1259 OID 123129)
-- Name: action_email_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_email_templates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2307 (class 0 OID 0)
-- Dependencies: 189
-- Name: action_email_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_email_templates_id_seq OWNED BY action_email_templates.id;


--
-- TOC entry 2308 (class 0 OID 0)
-- Dependencies: 189
-- Name: action_email_templates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_email_templates_id_seq', 6, true);


--
-- TOC entry 163 (class 1259 OID 122890)
-- Name: action_types; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE action_types (
    id integer NOT NULL,
    name character varying(50),
    require_deadline boolean DEFAULT false NOT NULL,
    active boolean DEFAULT true NOT NULL
);


--
-- TOC entry 164 (class 1259 OID 122895)
-- Name: action_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2309 (class 0 OID 0)
-- Dependencies: 164
-- Name: action_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_types_id_seq OWNED BY action_types.id;


--
-- TOC entry 2310 (class 0 OID 0)
-- Dependencies: 164
-- Name: action_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_types_id_seq', 5, true);


--
-- TOC entry 165 (class 1259 OID 122897)
-- Name: actions; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actions (
    id integer NOT NULL,
    user_id integer NOT NULL,
    course_membership_id integer NOT NULL,
    action_type_id integer NOT NULL,
    description text,
    created timestamp with time zone DEFAULT now() NOT NULL,
    modified timestamp with time zone,
    deadline timestamp with time zone,
    handled_id integer,
    handled_time timestamp with time zone
);


--
-- TOC entry 166 (class 1259 OID 122904)
-- Name: actions_exercises; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actions_exercises (
    id integer NOT NULL,
    action_id integer NOT NULL,
    exercise_id integer NOT NULL
);


--
-- TOC entry 167 (class 1259 OID 122907)
-- Name: actions_exercises_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actions_exercises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2311 (class 0 OID 0)
-- Dependencies: 167
-- Name: actions_exercises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actions_exercises_id_seq OWNED BY actions_exercises.id;


--
-- TOC entry 2312 (class 0 OID 0)
-- Dependencies: 167
-- Name: actions_exercises_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('actions_exercises_id_seq', 7, true);


--
-- TOC entry 168 (class 1259 OID 122909)
-- Name: actions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2313 (class 0 OID 0)
-- Dependencies: 168
-- Name: actions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actions_id_seq OWNED BY actions.id;


--
-- TOC entry 2314 (class 0 OID 0)
-- Dependencies: 168
-- Name: actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('actions_id_seq', 5, true);


--
-- TOC entry 169 (class 1259 OID 122911)
-- Name: chat_messages; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE chat_messages (
    id integer NOT NULL,
    user_id integer,
    content text,
    created timestamp with time zone
);


--
-- TOC entry 170 (class 1259 OID 122917)
-- Name: chat_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE chat_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2315 (class 0 OID 0)
-- Dependencies: 170
-- Name: chat_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE chat_messages_id_seq OWNED BY chat_messages.id;


--
-- TOC entry 2316 (class 0 OID 0)
-- Dependencies: 170
-- Name: chat_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('chat_messages_id_seq', 1, false);


--
-- TOC entry 171 (class 1259 OID 122919)
-- Name: course_memberships; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE course_memberships (
    id integer NOT NULL,
    course_id integer NOT NULL,
    student_id integer NOT NULL,
    quit_id integer,
    quit_time timestamp with time zone,
    comment text
);


--
-- TOC entry 172 (class 1259 OID 122925)
-- Name: course_memberships_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE course_memberships_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2317 (class 0 OID 0)
-- Dependencies: 172
-- Name: course_memberships_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE course_memberships_id_seq OWNED BY course_memberships.id;


--
-- TOC entry 2318 (class 0 OID 0)
-- Dependencies: 172
-- Name: course_memberships_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('course_memberships_id_seq', 5, true);


--
-- TOC entry 173 (class 1259 OID 122927)
-- Name: courses; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE courses (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    starttime timestamp with time zone NOT NULL,
    endtime timestamp with time zone NOT NULL
);


--
-- TOC entry 174 (class 1259 OID 122930)
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2319 (class 0 OID 0)
-- Dependencies: 174
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE courses_id_seq OWNED BY courses.id;


--
-- TOC entry 2320 (class 0 OID 0)
-- Dependencies: 174
-- Name: courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('courses_id_seq', 2, true);


--
-- TOC entry 175 (class 1259 OID 122932)
-- Name: courses_users; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE courses_users (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 176 (class 1259 OID 122935)
-- Name: courses_users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE courses_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2321 (class 0 OID 0)
-- Dependencies: 176
-- Name: courses_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE courses_users_id_seq OWNED BY courses_users.id;


--
-- TOC entry 2322 (class 0 OID 0)
-- Dependencies: 176
-- Name: courses_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('courses_users_id_seq', 8, true);


--
-- TOC entry 177 (class 1259 OID 122937)
-- Name: email_messages; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE email_messages (
    id integer NOT NULL,
    sender character varying(255),
    receiver character varying(255),
    subject character varying(255),
    content text,
    course_membership_id integer,
    action_id integer,
    sent_time timestamp with time zone,
    read_time timestamp with time zone
);


--
-- TOC entry 178 (class 1259 OID 122943)
-- Name: email_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE email_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2323 (class 0 OID 0)
-- Dependencies: 178
-- Name: email_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE email_messages_id_seq OWNED BY email_messages.id;


--
-- TOC entry 2324 (class 0 OID 0)
-- Dependencies: 178
-- Name: email_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('email_messages_id_seq', 1, false);


--
-- TOC entry 179 (class 1259 OID 122945)
-- Name: exercises; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exercises (
    id integer NOT NULL,
    course_id integer NOT NULL,
    exercise_number integer NOT NULL,
    exercise_name character varying(255),
    starttime timestamp with time zone,
    endtime timestamp with time zone,
    review_starttime timestamp with time zone,
    review_endtime timestamp with time zone
);


--
-- TOC entry 180 (class 1259 OID 122948)
-- Name: exercises_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE exercises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2325 (class 0 OID 0)
-- Dependencies: 180
-- Name: exercises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE exercises_id_seq OWNED BY exercises.id;


--
-- TOC entry 2326 (class 0 OID 0)
-- Dependencies: 180
-- Name: exercises_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('exercises_id_seq', 4, true);


--
-- TOC entry 181 (class 1259 OID 122950)
-- Name: groups; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE groups (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 182 (class 1259 OID 122953)
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2327 (class 0 OID 0)
-- Dependencies: 182
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- TOC entry 2328 (class 0 OID 0)
-- Dependencies: 182
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('groups_id_seq', 3, true);


--
-- TOC entry 183 (class 1259 OID 122955)
-- Name: groups_students; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE groups_students (
    id integer NOT NULL,
    group_id integer NOT NULL,
    student_id integer NOT NULL
);


--
-- TOC entry 184 (class 1259 OID 122958)
-- Name: groups_students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE groups_students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2329 (class 0 OID 0)
-- Dependencies: 184
-- Name: groups_students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE groups_students_id_seq OWNED BY groups_students.id;


--
-- TOC entry 2330 (class 0 OID 0)
-- Dependencies: 184
-- Name: groups_students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('groups_students_id_seq', 5, true);


--
-- TOC entry 185 (class 1259 OID 122960)
-- Name: students; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE students (
    id integer NOT NULL,
    student_number character varying(30) NOT NULL,
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL
);


--
-- TOC entry 186 (class 1259 OID 122966)
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2331 (class 0 OID 0)
-- Dependencies: 186
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE students_id_seq OWNED BY students.id;


--
-- TOC entry 2332 (class 0 OID 0)
-- Dependencies: 186
-- Name: students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('students_id_seq', 5, true);


--
-- TOC entry 187 (class 1259 OID 122968)
-- Name: users; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    basic_user_account character varying(30),
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(50) NOT NULL,
    is_admin boolean DEFAULT false NOT NULL,
    last_login timestamp with time zone
);


--
-- TOC entry 188 (class 1259 OID 122975)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2333 (class 0 OID 0)
-- Dependencies: 188
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 2334 (class 0 OID 0)
-- Dependencies: 188
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('users_id_seq', 4, true);


--
-- TOC entry 2207 (class 2604 OID 122977)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments ALTER COLUMN id SET DEFAULT nextval('action_comments_id_seq'::regclass);


--
-- TOC entry 2225 (class 2604 OID 123134)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_email_templates ALTER COLUMN id SET DEFAULT nextval('action_email_templates_id_seq'::regclass);


--
-- TOC entry 2210 (class 2604 OID 122978)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_types ALTER COLUMN id SET DEFAULT nextval('action_types_id_seq'::regclass);


--
-- TOC entry 2212 (class 2604 OID 122979)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions ALTER COLUMN id SET DEFAULT nextval('actions_id_seq'::regclass);


--
-- TOC entry 2213 (class 2604 OID 122980)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions_exercises ALTER COLUMN id SET DEFAULT nextval('actions_exercises_id_seq'::regclass);


--
-- TOC entry 2214 (class 2604 OID 122981)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY chat_messages ALTER COLUMN id SET DEFAULT nextval('chat_messages_id_seq'::regclass);


--
-- TOC entry 2215 (class 2604 OID 122982)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships ALTER COLUMN id SET DEFAULT nextval('course_memberships_id_seq'::regclass);


--
-- TOC entry 2216 (class 2604 OID 122983)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses ALTER COLUMN id SET DEFAULT nextval('courses_id_seq'::regclass);


--
-- TOC entry 2217 (class 2604 OID 122984)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users ALTER COLUMN id SET DEFAULT nextval('courses_users_id_seq'::regclass);


--
-- TOC entry 2218 (class 2604 OID 122985)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY email_messages ALTER COLUMN id SET DEFAULT nextval('email_messages_id_seq'::regclass);


--
-- TOC entry 2219 (class 2604 OID 122986)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY exercises ALTER COLUMN id SET DEFAULT nextval('exercises_id_seq'::regclass);


--
-- TOC entry 2220 (class 2604 OID 122987)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- TOC entry 2221 (class 2604 OID 122988)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students ALTER COLUMN id SET DEFAULT nextval('groups_students_id_seq'::regclass);


--
-- TOC entry 2222 (class 2604 OID 122989)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY students ALTER COLUMN id SET DEFAULT nextval('students_id_seq'::regclass);


--
-- TOC entry 2224 (class 2604 OID 122990)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 2282 (class 0 OID 122881)
-- Dependencies: 161
-- Data for Name: action_comments; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_comments VALUES (1, 1, 2, 'No on tuo melko hyvä..', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (2, 1, 4, 'Voisi olla tarkempi :)', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (3, 3, 4, 'Ei vastannut edes posteihin...', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (4, 5, 2, 'Tää on melko kärttyinen muutenkin!', '2012-11-22 12:03:26.42+02');


--
-- TOC entry 2296 (class 0 OID 123131)
-- Dependencies: 190
-- Data for Name: action_email_templates; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_email_templates VALUES (1, 1, 'Korjauspyyntö', 'Lorem ipsum dolor');
INSERT INTO action_email_templates VALUES (3, 2, 'Hylkäys', 'Lorem ipsum dolor');
INSERT INTO action_email_templates VALUES (4, 3, 'Huomautus', 'Lorem ipsum dolor');
INSERT INTO action_email_templates VALUES (5, 4, 'Lisäaika', 'Lorem ipsum dolor');
INSERT INTO action_email_templates VALUES (6, 5, 'Tekemättä', 'Lorem ipsum dolor');


--
-- TOC entry 2283 (class 0 OID 122890)
-- Dependencies: 163
-- Data for Name: action_types; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_types VALUES (1, 'Korjauspyyntö', true, true);
INSERT INTO action_types VALUES (2, 'Hylkäys', false, true);
INSERT INTO action_types VALUES (3, 'Huomautus', false, true);
INSERT INTO action_types VALUES (4, 'Lisäaika', true, true);
INSERT INTO action_types VALUES (5, 'Tekemättä', false, false);


--
-- TOC entry 2284 (class 0 OID 122897)
-- Dependencies: 165
-- Data for Name: actions; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO actions VALUES (1, 4, 4, 3, 'Arviointi voisi olla tarkempi', '2012-11-22 12:03:26.42+02', NULL, NULL, NULL, NULL);
INSERT INTO actions VALUES (2, 4, 4, 1, 'Epätarkka arvionti, parempi vaaditaan', '2012-11-22 12:03:26.42+02', NULL, '2013-02-10 00:00:00+02', NULL, NULL);
INSERT INTO actions VALUES (4, 2, 2, 4, 'Flunssassa, lisäaikaa annettu', '2012-11-22 12:03:26.42+02', '2013-01-10 22:22:00+02', '2013-01-14 23:00:00+02', NULL, NULL);
INSERT INTO actions VALUES (5, 3, 5, 3, 'Rakentavampi arviointi olisi paikallaan', '2012-11-22 12:03:26.42+02', NULL, NULL, 3, '2013-02-14 20:59:18.434+02');
INSERT INTO actions VALUES (3, 4, 4, 2, 'Ei tehnyt tehtävää huomautuksesta huolimatta, muutos', '2013-02-10 16:24:01+02', '2013-02-14 21:04:01+02', NULL, NULL, NULL);


--
-- TOC entry 2285 (class 0 OID 122904)
-- Dependencies: 166
-- Data for Name: actions_exercises; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO actions_exercises VALUES (1, 1, 2);
INSERT INTO actions_exercises VALUES (2, 2, 3);
INSERT INTO actions_exercises VALUES (4, 4, 2);
INSERT INTO actions_exercises VALUES (5, 5, 2);
INSERT INTO actions_exercises VALUES (6, 5, 3);
INSERT INTO actions_exercises VALUES (7, 3, 3);


--
-- TOC entry 2286 (class 0 OID 122911)
-- Dependencies: 169
-- Data for Name: chat_messages; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2287 (class 0 OID 122919)
-- Dependencies: 171
-- Data for Name: course_memberships; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO course_memberships VALUES (1, 2, 1, 2, '2013-02-14 20:59:18.434+02', '');
INSERT INTO course_memberships VALUES (2, 2, 2, NULL, NULL, '');
INSERT INTO course_memberships VALUES (3, 2, 3, NULL, NULL, '');
INSERT INTO course_memberships VALUES (4, 2, 4, NULL, NULL, '');
INSERT INTO course_memberships VALUES (5, 2, 5, NULL, NULL, 'Yksinhuoltaja sorsa');


--
-- TOC entry 2288 (class 0 OID 122927)
-- Dependencies: 173
-- Data for Name: courses; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO courses VALUES (1, 'T3 K2012', '2012-01-02 23:00:00+02', '2012-08-31 01:00:00+03');
INSERT INTO courses VALUES (2, 'T3 S2012', '2012-10-21 00:00:00+03', '2013-02-28 00:00:00+02');


--
-- TOC entry 2289 (class 0 OID 122932)
-- Dependencies: 175
-- Data for Name: courses_users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO courses_users VALUES (1, 1, 1);
INSERT INTO courses_users VALUES (2, 1, 2);
INSERT INTO courses_users VALUES (3, 1, 3);
INSERT INTO courses_users VALUES (4, 1, 4);
INSERT INTO courses_users VALUES (5, 2, 1);
INSERT INTO courses_users VALUES (6, 2, 2);
INSERT INTO courses_users VALUES (7, 2, 3);
INSERT INTO courses_users VALUES (8, 2, 4);


--
-- TOC entry 2290 (class 0 OID 122937)
-- Dependencies: 177
-- Data for Name: email_messages; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2291 (class 0 OID 122945)
-- Dependencies: 179
-- Data for Name: exercises; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO exercises VALUES (1, 1, 1, 'Eka harkka', '2012-08-15 01:00:00+03', '2012-08-20 01:00:00+03', '2012-08-20 01:00:00+03', '2012-08-25 01:00:00+03');
INSERT INTO exercises VALUES (2, 2, 1, 'Eka harkka (K2)', '2013-01-10 00:00:00+02', '2013-01-13 00:00:00+02', '2013-01-12 00:00:00+02', '2013-01-15 00:00:00+02');
INSERT INTO exercises VALUES (3, 2, 2, 'Toka harkka', '2013-02-05 00:00:00+02', '2013-02-10 00:00:00+02', '2013-02-09 00:00:00+02', '2013-02-15 00:00:00+02');
INSERT INTO exercises VALUES (4, 2, 3, 'Kolmas harkka', '2013-02-15 00:00:00+02', '2013-02-18 00:00:00+02', '2013-02-17 00:00:00+02', '2013-02-21 00:00:00+02');


--
-- TOC entry 2292 (class 0 OID 122950)
-- Dependencies: 181
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO groups VALUES (1, 2, 2);
INSERT INTO groups VALUES (2, 2, 3);
INSERT INTO groups VALUES (3, 2, 4);


--
-- TOC entry 2293 (class 0 OID 122955)
-- Dependencies: 183
-- Data for Name: groups_students; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO groups_students VALUES (1, 1, 1);
INSERT INTO groups_students VALUES (2, 1, 2);
INSERT INTO groups_students VALUES (3, 2, 3);
INSERT INTO groups_students VALUES (4, 3, 4);
INSERT INTO groups_students VALUES (5, 2, 5);


--
-- TOC entry 2294 (class 0 OID 122960)
-- Dependencies: 185
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO students VALUES (1, '34243', 'Testaaja', 'Testi', 'testi.testaaja@uta.fi');
INSERT INTO students VALUES (2, '12345', 'Opiskelija', 'Ossi', 'ossi.opiskelija@uta.fi');
INSERT INTO students VALUES (3, '98765', 'Mänty', 'Jarmo', 'jarmo.manty@uta.fi');
INSERT INTO students VALUES (4, '43214', 'Luttinen', 'Usko', 'usko.luttinen@uta.fi');
INSERT INTO students VALUES (5, '26371', 'Sorsa', 'Pulla', 'pulla.sorsa@uta.fi');


--
-- TOC entry 2295 (class 0 OID 122968)
-- Dependencies: 187
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO users VALUES (1, '12345', 'Opettaja', 'Vastuu', 'vastuuope@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', true, NULL);
INSERT INTO users VALUES (2, '23456', 'Assistentti', 'Asseri', 'asseri.assistentti@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false, NULL);
INSERT INTO users VALUES (3, '34567', 'Assistentti', 'Testi', 'testi.assistentti@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false, NULL);
INSERT INTO users VALUES (4, '45678', 'Auttaja', 'Aapo', 'aapo.auttaja@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false, NULL);


--
-- TOC entry 2227 (class 2606 OID 122992)
-- Name: action_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_pkey PRIMARY KEY (id);


--
-- TOC entry 2259 (class 2606 OID 123139)
-- Name: action_email_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_email_templates
    ADD CONSTRAINT action_email_templates_pkey PRIMARY KEY (id);


--
-- TOC entry 2229 (class 2606 OID 122994)
-- Name: action_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_types
    ADD CONSTRAINT action_types_pkey PRIMARY KEY (id);


--
-- TOC entry 2233 (class 2606 OID 122996)
-- Name: actions_exercises_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actions_exercises
    ADD CONSTRAINT actions_exercises_pkey PRIMARY KEY (id);


--
-- TOC entry 2231 (class 2606 OID 122998)
-- Name: actions_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_pkey PRIMARY KEY (id);


--
-- TOC entry 2235 (class 2606 OID 123000)
-- Name: chat_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY chat_messages
    ADD CONSTRAINT chat_messages_pkey PRIMARY KEY (id);


--
-- TOC entry 2237 (class 2606 OID 123002)
-- Name: course_memberships_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_pkey PRIMARY KEY (id);


--
-- TOC entry 2239 (class 2606 OID 123004)
-- Name: courses_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- TOC entry 2241 (class 2606 OID 123006)
-- Name: courses_users_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_pkey PRIMARY KEY (id);


--
-- TOC entry 2243 (class 2606 OID 123008)
-- Name: email_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY email_messages
    ADD CONSTRAINT email_messages_pkey PRIMARY KEY (id);


--
-- TOC entry 2245 (class 2606 OID 123010)
-- Name: exercises_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_pkey PRIMARY KEY (id);


--
-- TOC entry 2247 (class 2606 OID 123012)
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- TOC entry 2249 (class 2606 OID 123014)
-- Name: groups_students_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_pkey PRIMARY KEY (id);


--
-- TOC entry 2251 (class 2606 OID 123016)
-- Name: students_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- TOC entry 2253 (class 2606 OID 123018)
-- Name: students_student_number_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_student_number_key UNIQUE (student_number);


--
-- TOC entry 2255 (class 2606 OID 123020)
-- Name: users_basic_user_account_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_basic_user_account_key UNIQUE (basic_user_account);


--
-- TOC entry 2257 (class 2606 OID 123022)
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 2260 (class 2606 OID 123023)
-- Name: action_comments_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2261 (class 2606 OID 123028)
-- Name: action_comments_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2281 (class 2606 OID 123140)
-- Name: action_email_templates_action_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_email_templates
    ADD CONSTRAINT action_email_templates_action_type_id_fkey FOREIGN KEY (action_type_id) REFERENCES action_types(id);


--
-- TOC entry 2262 (class 2606 OID 123033)
-- Name: actions_action_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_action_type_id_fkey FOREIGN KEY (action_type_id) REFERENCES action_types(id);


--
-- TOC entry 2263 (class 2606 OID 123038)
-- Name: actions_course_membership_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_course_membership_id_fkey FOREIGN KEY (course_membership_id) REFERENCES course_memberships(id);


--
-- TOC entry 2266 (class 2606 OID 123043)
-- Name: actions_exercises_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions_exercises
    ADD CONSTRAINT actions_exercises_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2267 (class 2606 OID 123048)
-- Name: actions_exercises_exercise_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions_exercises
    ADD CONSTRAINT actions_exercises_exercise_id_fkey FOREIGN KEY (exercise_id) REFERENCES exercises(id);


--
-- TOC entry 2264 (class 2606 OID 123053)
-- Name: actions_handled_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_handled_id_fkey FOREIGN KEY (handled_id) REFERENCES users(id);


--
-- TOC entry 2265 (class 2606 OID 123058)
-- Name: actions_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2268 (class 2606 OID 123063)
-- Name: chat_messages_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY chat_messages
    ADD CONSTRAINT chat_messages_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2269 (class 2606 OID 123068)
-- Name: course_memberships_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2270 (class 2606 OID 123073)
-- Name: course_memberships_quit_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_quit_id_fkey FOREIGN KEY (quit_id) REFERENCES users(id);


--
-- TOC entry 2271 (class 2606 OID 123078)
-- Name: course_memberships_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2272 (class 2606 OID 123083)
-- Name: courses_users_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2273 (class 2606 OID 123088)
-- Name: courses_users_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2274 (class 2606 OID 123093)
-- Name: email_messages_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY email_messages
    ADD CONSTRAINT email_messages_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2275 (class 2606 OID 123098)
-- Name: email_messages_course_membership_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY email_messages
    ADD CONSTRAINT email_messages_course_membership_id_fkey FOREIGN KEY (course_membership_id) REFERENCES course_memberships(id);


--
-- TOC entry 2276 (class 2606 OID 123103)
-- Name: exercises_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2277 (class 2606 OID 123108)
-- Name: groups_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2279 (class 2606 OID 123113)
-- Name: groups_students_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_group_id_fkey FOREIGN KEY (group_id) REFERENCES groups(id);


--
-- TOC entry 2280 (class 2606 OID 123118)
-- Name: groups_students_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2278 (class 2606 OID 123123)
-- Name: groups_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2303 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM janne;
GRANT ALL ON SCHEMA public TO janne;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2013-02-27 14:48:59 EET

--
-- PostgreSQL database dump complete
--

