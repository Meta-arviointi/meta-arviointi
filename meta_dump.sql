--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.6
-- Dumped by pg_dump version 9.1.6
-- Started on 2012-10-30 16:56:52 EET

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 175 (class 3079 OID 11686)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 1975 (class 0 OID 0)
-- Dependencies: 175
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 161 (class 1259 OID 16542)
-- Dependencies: 6
-- Name: actions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE actions (
    id integer NOT NULL,
    user_id integer NOT NULL,
    student_id integer NOT NULL,
    exercise_id integer NOT NULL,
    type character(20),
    comment character varying(255),
    created timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deadline timestamp with time zone
);


ALTER TABLE public.actions OWNER TO postgres;

--
-- TOC entry 162 (class 1259 OID 16545)
-- Dependencies: 161 6
-- Name: actions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE actions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.actions_id_seq OWNER TO postgres;

--
-- TOC entry 1976 (class 0 OID 0)
-- Dependencies: 162
-- Name: actions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE actions_id_seq OWNED BY actions.id;


--
-- TOC entry 1977 (class 0 OID 0)
-- Dependencies: 162
-- Name: actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('actions_id_seq', 1, false);


--
-- TOC entry 163 (class 1259 OID 16547)
-- Dependencies: 6
-- Name: courses; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE courses (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    starttime timestamp with time zone NOT NULL,
    endtime timestamp with time zone NOT NULL
);


ALTER TABLE public.courses OWNER TO postgres;

--
-- TOC entry 164 (class 1259 OID 16550)
-- Dependencies: 163 6
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.courses_id_seq OWNER TO postgres;

--
-- TOC entry 1978 (class 0 OID 0)
-- Dependencies: 164
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE courses_id_seq OWNED BY courses.id;


--
-- TOC entry 1979 (class 0 OID 0)
-- Dependencies: 164
-- Name: courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('courses_id_seq', 1, true);


--
-- TOC entry 165 (class 1259 OID 16552)
-- Dependencies: 6
-- Name: exercises; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exercises (
    id integer NOT NULL,
    course_id integer NOT NULL,
    exercise_number integer NOT NULL,
    deadline timestamp with time zone NOT NULL
);


ALTER TABLE public.exercises OWNER TO postgres;

--
-- TOC entry 166 (class 1259 OID 16555)
-- Dependencies: 6 165
-- Name: exercises_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exercises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.exercises_id_seq OWNER TO postgres;

--
-- TOC entry 1980 (class 0 OID 0)
-- Dependencies: 166
-- Name: exercises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE exercises_id_seq OWNED BY exercises.id;


--
-- TOC entry 1981 (class 0 OID 0)
-- Dependencies: 166
-- Name: exercises_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('exercises_id_seq', 1, true);


--
-- TOC entry 167 (class 1259 OID 16557)
-- Dependencies: 6
-- Name: groups; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE groups (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.groups OWNER TO postgres;

--
-- TOC entry 168 (class 1259 OID 16560)
-- Dependencies: 167 6
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.groups_id_seq OWNER TO postgres;

--
-- TOC entry 1982 (class 0 OID 0)
-- Dependencies: 168
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- TOC entry 1983 (class 0 OID 0)
-- Dependencies: 168
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('groups_id_seq', 3, true);


--
-- TOC entry 169 (class 1259 OID 16562)
-- Dependencies: 6
-- Name: notes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE notes (
    id integer NOT NULL,
    student_id integer NOT NULL,
    user_id integer NOT NULL,
    note text NOT NULL,
    created timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.notes OWNER TO postgres;

--
-- TOC entry 170 (class 1259 OID 16565)
-- Dependencies: 169 6
-- Name: notes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE notes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.notes_id_seq OWNER TO postgres;

--
-- TOC entry 1984 (class 0 OID 0)
-- Dependencies: 170
-- Name: notes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE notes_id_seq OWNED BY notes.id;


--
-- TOC entry 1985 (class 0 OID 0)
-- Dependencies: 170
-- Name: notes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('notes_id_seq', 4, true);


--
-- TOC entry 171 (class 1259 OID 16567)
-- Dependencies: 6
-- Name: students; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE students (
    id integer NOT NULL,
    student_number integer NOT NULL,
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    group_id integer
);


ALTER TABLE public.students OWNER TO postgres;

--
-- TOC entry 172 (class 1259 OID 16575)
-- Dependencies: 171 6
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.students_id_seq OWNER TO postgres;

--
-- TOC entry 1986 (class 0 OID 0)
-- Dependencies: 172
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE students_id_seq OWNED BY students.id;


--
-- TOC entry 1987 (class 0 OID 0)
-- Dependencies: 172
-- Name: students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('students_id_seq', 6, true);


--
-- TOC entry 173 (class 1259 OID 16577)
-- Dependencies: 6
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    basic_user_account character varying(30) UNIQUE,
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    is_admin boolean NOT NULL DEFAULT false,
    password character varying(50) NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 174 (class 1259 OID 16583)
-- Dependencies: 6 173
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 1988 (class 0 OID 0)
-- Dependencies: 174
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 1989 (class 0 OID 0)
-- Dependencies: 174
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_id_seq', 4, true);


--
-- TOC entry 1931 (class 2604 OID 16585)
-- Dependencies: 162 161
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actions ALTER COLUMN id SET DEFAULT nextval('actions_id_seq'::regclass);


--
-- TOC entry 1932 (class 2604 OID 16586)
-- Dependencies: 164 163
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY courses ALTER COLUMN id SET DEFAULT nextval('courses_id_seq'::regclass);


--
-- TOC entry 1933 (class 2604 OID 16587)
-- Dependencies: 166 165
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY exercises ALTER COLUMN id SET DEFAULT nextval('exercises_id_seq'::regclass);


--
-- TOC entry 1934 (class 2604 OID 16588)
-- Dependencies: 168 167
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- TOC entry 1935 (class 2604 OID 16589)
-- Dependencies: 170 169
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY notes ALTER COLUMN id SET DEFAULT nextval('notes_id_seq'::regclass);


--
-- TOC entry 1936 (class 2604 OID 16590)
-- Dependencies: 172 171
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY students ALTER COLUMN id SET DEFAULT nextval('students_id_seq'::regclass);


--
-- TOC entry 1937 (class 2604 OID 16591)
-- Dependencies: 174 173
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 1961 (class 0 OID 16542)
-- Dependencies: 161 1968
-- Data for Name: actions; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 1962 (class 0 OID 16547)
-- Dependencies: 163 1968
-- Data for Name: courses; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO courses VALUES (1, 'T3 S2012', '2012-10-21 00:00:00+03', '2013-01-31 00:00:00+02');


--
-- TOC entry 1963 (class 0 OID 16552)
-- Dependencies: 165 1968
-- Data for Name: exercises; Type: TABLE DATA; Schema: public; Owner: postgres
--
INSERT INTO exercises VALUES (1, 1, 1, '2013-01-31 00:00:00+02');


--
-- TOC entry 1964 (class 0 OID 16557)
-- Dependencies: 167 1968
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO groups VALUES (1, 1, 2);
INSERT INTO groups VALUES (2, 1, 3);
INSERT INTO groups VALUES (3, 1, 4);


--
-- TOC entry 1965 (class 0 OID 16562)
-- Dependencies: 169 1968
-- Data for Name: notes; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO notes VALUES (1, 2, 3, 'tämä on nootti', '2012-10-29 22:17:15.91+02');
INSERT INTO notes VALUES (2, 4, 2, 'Reipas kaveri!', '2012-10-30 15:19:27.167278+02');
INSERT INTO notes VALUES (3, 4, 3, 'Totta!', '2012-10-30 15:19:59.319854+02');


--
-- TOC entry 1966 (class 0 OID 16567)
-- Dependencies: 171 1968
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO students VALUES (3, 12345, 'Opiskelija                                                                                                                                                                                                                                                     ', 'Ossi                                                                                                                                                                                                                                                           ', 'ossi.opiskelija@uta.fi                                                                                                                                                                                                                                         ', 1);
INSERT INTO students VALUES (4, 98765, 'Mänty                                                                                                                                                                                                                                                          ', 'Jarmo                                                                                                                                                                                                                                                          ', 'jarmo.manty@uta.fi                                                                                                                                                                                                                                             ', 3);
INSERT INTO students VALUES (5, 43214, 'Luttinen                                                                                                                                                                                                                                                       ', 'Usko                                                                                                                                                                                                                                                           ', 'usko.luttinen@uta.fi                                                                                                                                                                                                                                           ', 3);
INSERT INTO students VALUES (6, 26371, 'Sorsa                                                                                                                                                                                                                                                          ', 'Pulla                                                                                                                                                                                                                                                          ', 'pulla.sorsa@uta.fi                                                                                                                                                                                                                                             ', 2);
INSERT INTO students VALUES (2, 3423, 'Testaaja                                                                                                                                                                                                                                                       ', 'Testi                                                                                                                                                                                                                                                          ', 'testi.testaaja@uta.fi', 1);


--
-- TOC entry 1967 (class 0 OID 16577)
-- Dependencies: 173 1968
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO users VALUES (1, '12345', 'Opettaja', 'Vastuu', 'vastuuope@uta.fi', true, 'ce192a5d433c20a11b143e63319f960f3a2361b8');
INSERT INTO users VALUES (2, '23456', 'Assistentti', 'Asseri', 'asseri.assistentti@uta.fi', false, 'ce192a5d433c20a11b143e63319f960f3a2361b8');
INSERT INTO users VALUES (3, '34567', 'Assistentti', 'Testi', 'testi.assistentti@uta.fi', false, 'ce192a5d433c20a11b143e63319f960f3a2361b8');
INSERT INTO users VALUES (4, '45678', 'Auttaja', 'Aapo', 'aapo.auttaja@uta.fi', false, 'ce192a5d433c20a11b143e63319f960f3a2361b8');


--
-- TOC entry 1939 (class 2606 OID 16593)
-- Dependencies: 161 161 1969
-- Name: actions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_pkey PRIMARY KEY (id);


--
-- TOC entry 1941 (class 2606 OID 16595)
-- Dependencies: 163 163 1969
-- Name: courses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- TOC entry 1943 (class 2606 OID 16597)
-- Dependencies: 165 165 1969
-- Name: exercises_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_pkey PRIMARY KEY (id);


--
-- TOC entry 1945 (class 2606 OID 16599)
-- Dependencies: 167 167 1969
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- TOC entry 1947 (class 2606 OID 16601)
-- Dependencies: 171 171 1969
-- Name: students_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- TOC entry 1949 (class 2606 OID 16574)
-- Dependencies: 171 171 1969
-- Name: students_student_number_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_student_number_key UNIQUE (student_number);


--
-- TOC entry 1951 (class 2606 OID 16603)
-- Dependencies: 173 173 1969
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 1952 (class 2606 OID 16604)
-- Dependencies: 1942 165 161 1969
-- Name: actions_exercise_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_exercise_id_fkey FOREIGN KEY (exercise_id) REFERENCES exercises(id);


--
-- TOC entry 1953 (class 2606 OID 16609)
-- Dependencies: 171 161 1946 1969
-- Name: actions_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 1954 (class 2606 OID 16614)
-- Dependencies: 1950 161 173 1969
-- Name: actions_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 1955 (class 2606 OID 16619)
-- Dependencies: 163 1940 165 1969
-- Name: exercises_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 1956 (class 2606 OID 16624)
-- Dependencies: 167 163 1940 1969
-- Name: groups_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 1957 (class 2606 OID 16629)
-- Dependencies: 173 167 1950 1969
-- Name: groups_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 1958 (class 2606 OID 16634)
-- Dependencies: 171 169 1946 1969
-- Name: notes_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY notes
    ADD CONSTRAINT notes_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 1959 (class 2606 OID 16639)
-- Dependencies: 1950 173 169 1969
-- Name: notes_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY notes
    ADD CONSTRAINT notes_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 1960 (class 2606 OID 16644)
-- Dependencies: 167 1944 171 1969
-- Name: students_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_group_id_fkey FOREIGN KEY (group_id) REFERENCES groups(id);


--
-- TOC entry 1974 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2012-10-30 16:56:53 EET

--
-- PostgreSQL database dump complete
--

