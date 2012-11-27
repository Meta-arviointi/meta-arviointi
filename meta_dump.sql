--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.0
-- Dumped by pg_dump version 9.2.0
-- Started on 2012-11-22 18:16:30

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 188 (class 3079 OID 11727)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2042 (class 0 OID 0)
-- Dependencies: 188
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 183 (class 1259 OID 49520)
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
-- TOC entry 182 (class 1259 OID 49518)
-- Name: action_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2043 (class 0 OID 0)
-- Dependencies: 182
-- Name: action_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_comments_id_seq OWNED BY action_comments.id;


--
-- TOC entry 2044 (class 0 OID 0)
-- Dependencies: 182
-- Name: action_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_comments_id_seq', 1, false);


--
-- TOC entry 179 (class 1259 OID 49483)
-- Name: action_types; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE action_types (
    id integer NOT NULL,
    name character varying(50)
);


--
-- TOC entry 178 (class 1259 OID 49481)
-- Name: action_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2045 (class 0 OID 0)
-- Dependencies: 178
-- Name: action_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_types_id_seq OWNED BY action_types.id;


--
-- TOC entry 2046 (class 0 OID 0)
-- Dependencies: 178
-- Name: action_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_types_id_seq', 1, false);


--
-- TOC entry 181 (class 1259 OID 49491)
-- Name: actions; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actions (
    id integer NOT NULL,
    user_id integer NOT NULL,
    student_id integer NOT NULL,
    exercise_id integer NOT NULL,
    action_type_id integer NOT NULL,
    description character varying(255),
    created timestamp with time zone DEFAULT now() NOT NULL,
    deadline timestamp with time zone
);


--
-- TOC entry 180 (class 1259 OID 49489)
-- Name: actions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2047 (class 0 OID 0)
-- Dependencies: 180
-- Name: actions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actions_id_seq OWNED BY actions.id;


--
-- TOC entry 2048 (class 0 OID 0)
-- Dependencies: 180
-- Name: actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('actions_id_seq', 1, false);


--
-- TOC entry 187 (class 1259 OID 49560)
-- Name: course_memberships; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE course_memberships (
    id integer NOT NULL,
    course_id integer NOT NULL,
    student_id integer NOT NULL,
    quit boolean DEFAULT false NOT NULL,
    comment text
);


--
-- TOC entry 186 (class 1259 OID 49558)
-- Name: course_memberships_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE course_memberships_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2049 (class 0 OID 0)
-- Dependencies: 186
-- Name: course_memberships_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE course_memberships_id_seq OWNED BY course_memberships.id;


--
-- TOC entry 2050 (class 0 OID 0)
-- Dependencies: 186
-- Name: course_memberships_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('course_memberships_id_seq', 1, false);


--
-- TOC entry 169 (class 1259 OID 49417)
-- Name: courses; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE courses (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    starttime timestamp with time zone NOT NULL,
    endtime timestamp with time zone NOT NULL
);


--
-- TOC entry 168 (class 1259 OID 49415)
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2051 (class 0 OID 0)
-- Dependencies: 168
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE courses_id_seq OWNED BY courses.id;


--
-- TOC entry 2052 (class 0 OID 0)
-- Dependencies: 168
-- Name: courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('courses_id_seq', 1, false);


--
-- TOC entry 177 (class 1259 OID 49470)
-- Name: exercises; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exercises (
    id integer NOT NULL,
    course_id integer NOT NULL,
    exercise_number integer NOT NULL,
    deadline timestamp with time zone NOT NULL
);


--
-- TOC entry 176 (class 1259 OID 49468)
-- Name: exercises_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE exercises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2053 (class 0 OID 0)
-- Dependencies: 176
-- Name: exercises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE exercises_id_seq OWNED BY exercises.id;


--
-- TOC entry 2054 (class 0 OID 0)
-- Dependencies: 176
-- Name: exercises_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('exercises_id_seq', 1, false);


--
-- TOC entry 175 (class 1259 OID 49452)
-- Name: groups; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE groups (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 174 (class 1259 OID 49450)
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2055 (class 0 OID 0)
-- Dependencies: 174
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- TOC entry 2056 (class 0 OID 0)
-- Dependencies: 174
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('groups_id_seq', 1, false);


--
-- TOC entry 185 (class 1259 OID 49542)
-- Name: groups_students; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE groups_students (
    id integer NOT NULL,
    group_id integer NOT NULL,
    student_id integer NOT NULL
);


--
-- TOC entry 184 (class 1259 OID 49540)
-- Name: groups_students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE groups_students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2057 (class 0 OID 0)
-- Dependencies: 184
-- Name: groups_students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE groups_students_id_seq OWNED BY groups_students.id;


--
-- TOC entry 2058 (class 0 OID 0)
-- Dependencies: 184
-- Name: groups_students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('groups_students_id_seq', 1, false);


--
-- TOC entry 171 (class 1259 OID 49425)
-- Name: students; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE students (
    id integer NOT NULL,
    student_number integer NOT NULL,
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL
);


--
-- TOC entry 170 (class 1259 OID 49423)
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2059 (class 0 OID 0)
-- Dependencies: 170
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE students_id_seq OWNED BY students.id;


--
-- TOC entry 2060 (class 0 OID 0)
-- Dependencies: 170
-- Name: students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('students_id_seq', 1, false);


--
-- TOC entry 173 (class 1259 OID 49438)
-- Name: users; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    basic_user_account character varying(30),
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(50) NOT NULL,
    is_admin boolean DEFAULT false NOT NULL
);


--
-- TOC entry 172 (class 1259 OID 49436)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2061 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 2062 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('users_id_seq', 1, false);


--
-- TOC entry 1983 (class 2604 OID 49523)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments ALTER COLUMN id SET DEFAULT nextval('action_comments_id_seq'::regclass);


--
-- TOC entry 1980 (class 2604 OID 49486)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_types ALTER COLUMN id SET DEFAULT nextval('action_types_id_seq'::regclass);


--
-- TOC entry 1981 (class 2604 OID 49494)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions ALTER COLUMN id SET DEFAULT nextval('actions_id_seq'::regclass);


--
-- TOC entry 1986 (class 2604 OID 49563)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships ALTER COLUMN id SET DEFAULT nextval('course_memberships_id_seq'::regclass);


--
-- TOC entry 1974 (class 2604 OID 49420)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses ALTER COLUMN id SET DEFAULT nextval('courses_id_seq'::regclass);


--
-- TOC entry 1979 (class 2604 OID 49473)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY exercises ALTER COLUMN id SET DEFAULT nextval('exercises_id_seq'::regclass);


--
-- TOC entry 1978 (class 2604 OID 49455)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- TOC entry 1985 (class 2604 OID 49545)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students ALTER COLUMN id SET DEFAULT nextval('groups_students_id_seq'::regclass);


--
-- TOC entry 1975 (class 2604 OID 49428)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY students ALTER COLUMN id SET DEFAULT nextval('students_id_seq'::regclass);


--
-- TOC entry 1976 (class 2604 OID 49441)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 2032 (class 0 OID 49520)
-- Dependencies: 183
-- Data for Name: action_comments; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_comments VALUES (default, 1, 2, 'No on tuo melko hyvä..', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (default, 1, 4, 'Voisi olla tarkempi :)', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (default, 3, 4, 'Ei vastannut edes posteihin...', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (default, 5, 2, 'Tää on melko kärttyinen muutenkin!', '2012-11-22 12:03:26.42+02');


--
-- TOC entry 2030 (class 0 OID 49483)
-- Dependencies: 179
-- Data for Name: action_types; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_types VALUES (default, 'Korjauspyyntö');
INSERT INTO action_types VALUES (default, 'Hylkäys');
INSERT INTO action_types VALUES (default, 'Huomautus');
INSERT INTO action_types VALUES (default, 'Lisäaika');


--
-- TOC entry 2031 (class 0 OID 49491)
-- Dependencies: 181
-- Data for Name: actions; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO actions VALUES (default, 4, 4, 2, 3, 'Arviointi voisi olla tarkempi', '2012-11-22 12:03:26.42+02', NULL);
INSERT INTO actions VALUES (default, 4, 4, 3, 1, 'Epätarkka arvionti, parempi vaaditaan', '2012-11-22 12:03:26.42+02', '2013-02-10 00:00:00+02');
INSERT INTO actions VALUES (default, 4, 4, 3, 2, 'Ei tehnyt tehtävää huomautuksesta huolimatta', '2013-02-10 16:24:01+02', NULL);
INSERT INTO actions VALUES (default, 2, 2, 2, 4, 'Flunssassa, lisäaikaa annettu', '2012-11-22 12:03:26.42+02', '2013-01-14 23:00:00+02');
INSERT INTO actions VALUES (default, 3, 5, 2, 3, 'Rakentavampi arviointi olisi paikallaan', '2012-11-22 12:03:26.42+02', NULL);


--
-- TOC entry 2034 (class 0 OID 49560)
-- Dependencies: 187
-- Data for Name: course_memberships; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO course_memberships VALUES (default, 2, 1, false, '');
INSERT INTO course_memberships VALUES (default, 2, 2, false, '');
INSERT INTO course_memberships VALUES (default, 2, 3, false, '');
INSERT INTO course_memberships VALUES (default, 2, 4, false, '');
INSERT INTO course_memberships VALUES (default, 2, 5, false, 'Yksinhuoltaja sorsa');


--
-- TOC entry 2025 (class 0 OID 49417)
-- Dependencies: 169
-- Data for Name: courses; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO courses VALUES (default, 'T3 K2012', '2012-01-02 23:00:00+02', '2012-08-31 01:00:00+03');
INSERT INTO courses VALUES (default, 'T3 S2012', '2012-10-21 00:00:00+03', '2013-01-31 00:00:00+02');


--
-- TOC entry 2029 (class 0 OID 49470)
-- Dependencies: 177
-- Data for Name: exercises; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO exercises VALUES (default, 1, 1, '2012-08-15 01:00:00+03');
INSERT INTO exercises VALUES (default, 2, 1, '2013-01-10 00:00:00+02');
INSERT INTO exercises VALUES (default, 2, 2, '2013-02-05 00:00:00+02');
INSERT INTO exercises VALUES (default, 2, 3, '2013-02-15 00:00:00+02');


--
-- TOC entry 2028 (class 0 OID 49452)
-- Dependencies: 175
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO groups VALUES (default, 2, 2);
INSERT INTO groups VALUES (default, 2, 3);
INSERT INTO groups VALUES (default, 2, 4);


--
-- TOC entry 2033 (class 0 OID 49542)
-- Dependencies: 185
-- Data for Name: groups_students; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO groups_students VALUES (default, 1, 1);
INSERT INTO groups_students VALUES (default, 1, 2);
INSERT INTO groups_students VALUES (default, 2, 3);
INSERT INTO groups_students VALUES (default, 3, 4);
INSERT INTO groups_students VALUES (default, 2, 5);


--
-- TOC entry 2026 (class 0 OID 49425)
-- Dependencies: 171
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO students VALUES (default, 34243, 'Testaaja', 'Testi', 'testi.testaaja@uta.fi');
INSERT INTO students VALUES (default, 12345, 'Opiskelija', 'Ossi', 'ossi.opiskelija@uta.fi');
INSERT INTO students VALUES (default, 98765, 'Mänty', 'Jarmo', 'jarmo.manty@uta.fi');
INSERT INTO students VALUES (default, 43214, 'Luttinen', 'Usko', 'usko.luttinen@uta.fi');
INSERT INTO students VALUES (default, 26371, 'Sorsa', 'Pulla', 'pulla.sorsa@uta.fi');


--
-- TOC entry 2027 (class 0 OID 49438)
-- Dependencies: 173
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO users VALUES (default, '12345', 'Opettaja', 'Vastuu', 'vastuuope@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', true);
INSERT INTO users VALUES (default, '23456', 'Assistentti', 'Asseri', 'asseri.assistentti@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false);
INSERT INTO users VALUES (default, '34567', 'Assistentti', 'Testi', 'testi.assistentti@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false);
INSERT INTO users VALUES (default, '45678', 'Auttaja', 'Aapo', 'aapo.auttaja@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false);


--
-- TOC entry 2007 (class 2606 OID 49529)
-- Name: action_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_pkey PRIMARY KEY (id);


--
-- TOC entry 2003 (class 2606 OID 49488)
-- Name: action_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_types
    ADD CONSTRAINT action_types_pkey PRIMARY KEY (id);


--
-- TOC entry 2005 (class 2606 OID 49497)
-- Name: actions_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_pkey PRIMARY KEY (id);


--
-- TOC entry 2011 (class 2606 OID 49569)
-- Name: course_memberships_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_pkey PRIMARY KEY (id);


--
-- TOC entry 1989 (class 2606 OID 49422)
-- Name: courses_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- TOC entry 2001 (class 2606 OID 49475)
-- Name: exercises_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_pkey PRIMARY KEY (id);


--
-- TOC entry 1999 (class 2606 OID 49457)
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- TOC entry 2009 (class 2606 OID 49547)
-- Name: groups_students_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_pkey PRIMARY KEY (id);


--
-- TOC entry 1991 (class 2606 OID 49433)
-- Name: students_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- TOC entry 1993 (class 2606 OID 49435)
-- Name: students_student_number_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_student_number_key UNIQUE (student_number);


--
-- TOC entry 1995 (class 2606 OID 49449)
-- Name: users_basic_user_account_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_basic_user_account_key UNIQUE (basic_user_account);


--
-- TOC entry 1997 (class 2606 OID 49447)
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 2019 (class 2606 OID 49530)
-- Name: action_comments_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2020 (class 2606 OID 49535)
-- Name: action_comments_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2018 (class 2606 OID 49513)
-- Name: actions_action_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_action_type_id_fkey FOREIGN KEY (action_type_id) REFERENCES action_types(id);


--
-- TOC entry 2017 (class 2606 OID 49508)
-- Name: actions_exercise_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_exercise_id_fkey FOREIGN KEY (exercise_id) REFERENCES exercises(id);


--
-- TOC entry 2016 (class 2606 OID 49503)
-- Name: actions_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2015 (class 2606 OID 49498)
-- Name: actions_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2023 (class 2606 OID 49570)
-- Name: course_memberships_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2024 (class 2606 OID 49575)
-- Name: course_memberships_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2014 (class 2606 OID 49476)
-- Name: exercises_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2012 (class 2606 OID 49458)
-- Name: groups_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2021 (class 2606 OID 49548)
-- Name: groups_students_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_group_id_fkey FOREIGN KEY (group_id) REFERENCES groups(id);


--
-- TOC entry 2022 (class 2606 OID 49553)
-- Name: groups_students_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2013 (class 2606 OID 49463)
-- Name: groups_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2041 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2012-11-22 18:16:30

--
-- PostgreSQL database dump complete
--

