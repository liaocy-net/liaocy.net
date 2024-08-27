import { VerticalTimeline, VerticalTimelineElement }  from 'react-vertical-timeline-component';
import 'react-vertical-timeline-component/style.min.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faBriefcase, faCertificate, faSchool } from '@fortawesome/free-solid-svg-icons'

enum Type {
  Certification,
  Work,
  School,
}

type ResumeItem = {
  type: Type;
  title: string;
  subtitle: string;
  description: string;
  date: string;
};

const ResumeList: ResumeItem[] = [
  {
    type: Type.Work,
    date: 'November 2023 - Present',
    title: 'System Engineer, Assistant Manager',
    subtitle: 'Toyota Motor Corporation',
    description: 'Tokyo, Japan',
  },
  {
    type: Type.Certification,
    date: 'June 2024',
    title: 'Associate Cloud Engineer Certification',
    subtitle: 'Google Cloud',
    description: 'https://www.credly.com/badges/042526ea-3636-44bc-a259-5949ba12b031',
  },
  {
    type: Type.Certification,
    date: 'April 2024',
    title: 'AWS Certified SysOps Administrator – Associate',
    subtitle: 'AWS Training & Certification',
    description: 'https://www.credly.com/badges/c0d83ccc-c2ad-427e-90b3-e36defb54af7/linked_in_profile',
  },
  {
    type: Type.Certification,
    date: 'February 2024',
    title: 'AWS Certified Developer – Associate',
    subtitle: 'AWS Training & Certification',
    description: 'https://www.credly.com/badges/5caf6d6f-ecbf-452f-a623-27e4506ca036/linked_in_profile',
  },
  {
    type: Type.Certification,
    date: 'December 2023',
    title: 'AWS Certified Solutions Architect - Associate',
    subtitle: 'AWS Training & Certification',
    description: '5ZX6BRRLSMEQ17SZ',
  },
  {
    type: Type.Work,
    date: 'October 2021 - October 2023',
    title: 'Data Engineer',
    subtitle: 'CyberAgent, Inc.',
    description: 'Tokyo, Japan',
  },
  {
    type: Type.Certification,
    date: 'September 2023',
    title: 'AWS Certified Cloud Practitioner',
    subtitle: 'AWS Training & Certification',
    description: '',
  },
  {
    type: Type.Certification,
    date: 'Issued November 2022',
    title: 'Applied Information Technology Engineer Examination',
    subtitle: 'IPA (Information-technology Promotion Agency)',
    description: 'Credential ID: AP-2022-10-02822',
  },
  {
    type: Type.Work,
    date: 'April 2019 - September 2021',
    title: 'System Engineer',
    subtitle: 'Softbank Corp.',
    description: '',
  },
  {
    type: Type.Certification,
    date: 'May 2021',
    title: 'TOEIC Listening & Reading 815',
    subtitle: 'The TOEIC Program',
    description: 'Credential ID: 388326101',
  },
  {
    type: Type.Certification,
    date: 'Februray 2021',
    title: 'JDLA Deep Learning for ENGINEER 2021#1',
    subtitle: 'JDLA (Japan Deep Learning Association)',
    description: 'Credential ID: 388326101',
  },
  {
    type: Type.Certification,
    date: 'December 2020',
    title: 'MCPC IoTシステム技術検定基礎',
    subtitle: 'MCPC',
    description: 'Credential ID: 20000392T3',
  },
  {
    type: Type.Certification,
    date: 'September 2020',
    title: 'Oracle Certified Java Programmer, Gold SE 11',
    subtitle: 'Oracle',
    description: 'Credential ID: 276874011GLDSE11JPN',
  },
  {
    type: Type.Certification,
    date: 'Auguest 2020',
    title: 'Oracle Certified Java Programmer, Silver SE 11',
    subtitle: 'Oracle',
    description: 'Credential ID: 276874011SILVSE11JPN',
  },
  {
    type: Type.Certification,
    date: 'Issued Fabrary 2020',
    title: 'Python 3 Certified Engineer Basic Examination',
    subtitle: '一般社団法人Pythonエンジニア育成推進協会',
    description: '',
  },
  {
    type: Type.Certification,
    date: 'Issued March 2020',
    title: 'JDLA Deep learning for GENERAL 2020#1',
    subtitle: 'JDLA (Japan Deep Learning Association)',
    description: '',
  },
  {
    type: Type.Certification,
    date: 'Issued Februray 2020 - Expired August 2023',
    title: 'Cisco Certified Network Professional Routing and Switching (CCNP Routing and Switching)',
    subtitle: 'Cisco Systems, Inc.',
    description: '',
  },
  {
    type: Type.Certification,
    date: 'Issued Fabrary 2020',
    title: 'Python 3 Certified Engineer Basic Examination',
    subtitle: '一般社団法人Pythonエンジニア育成推進協会',
    description: '',
  },
  {
    type: Type.Certification,
    date: 'Issued November 2019',
    title: 'Fundamental Information Technology Engineer Examination',
    subtitle: 'IPA 独立行政法人 情報処理推進機構',
    description: '',
  },
  {
    type: Type.Certification,
    date: 'Issued August 2019 - Expired August 2023',
    title: 'Cisco logo Cisco Certified Network Associate Routing and Switching (CCNA Routing and Switching)',
    subtitle: 'Cisco Systems, Inc.',
    description: '',
  },
  {
    type: Type.Work,
    date: 'Issued April 2017 - Expired March 2019',
    title: 'Researcher',
    subtitle: 'Institutes of Innovation for Future Society, Nagoya University',
    description: '',
  },
  {
    type: Type.School,
    date: 'September 2014 - March 2019',
    title: 'Ph.D. Course',
    subtitle: 'Graduate School of Engineering, Graduate Schools, Naogya University',
    description: '',
  },
];

function Resume({type, date, title, subtitle, description}: ResumeItem) {
  let className = 'vertical-timeline-element--work';
  let icon = <FontAwesomeIcon icon={faBriefcase} />;
  let contentStyle = { background: 'rgb(33, 150, 243)', color: '#fff' };
  let contentArrowStyle = { borderRight: '7px solid  rgb(33, 150, 243)' };
  let iconStyle = { background: 'rgb(33, 150, 243)', color: '#fff' };
  switch (type) {
    case Type.Work:
      contentStyle = { background: 'rgb(33, 150, 243)', color: '#fff' };
      contentArrowStyle = { borderRight: '7px solid  rgb(33, 150, 243)' };
      iconStyle = { background: 'rgb(33, 150, 243)', color: '#fff' };
      className = 'vertical-timeline-element--work';
      icon = <FontAwesomeIcon icon={faBriefcase} />;
      break;
    case Type.Certification:
      contentStyle = { background: 'rgb(214, 177, 9)', color: '#fff' };
      contentArrowStyle = { borderRight: '7px solid  rgb(214, 177, 9)' };
      iconStyle = { background: 'rgb(214, 177, 9)', color: '#fff' };
      className = 'vertical-timeline-element--certification';
      icon = <FontAwesomeIcon icon={faCertificate} />;
      break;
    case Type.School:
      contentStyle = { background: 'rgb(235, 49, 2)', color: '#fff' };
      contentArrowStyle = { borderRight: '7px solid  rgb(235, 49, 2)' };
      iconStyle = { background: 'rgb(235, 49, 2)', color: '#fff' };
      className = 'vertical-timeline-element--school';
      icon = <FontAwesomeIcon icon={faSchool} />;
      break;
  }
  
  return (
    <VerticalTimelineElement
      className={className}
      contentStyle={contentStyle}
      contentArrowStyle={contentArrowStyle}
      date={date}
      iconStyle={iconStyle}
      icon={icon}
    >
      <h3 className="vertical-timeline-element-title">{title}</h3>
      <h4 className="vertical-timeline-element-subtitle">{subtitle}</h4>
      <p>
        {description}
      </p>
    </VerticalTimelineElement>
  );
}

export default function ResumeTimeLine() {
  return (
    <VerticalTimeline>
      {ResumeList.map((props, idx) => (
        <Resume key={idx} {...props} />
      ))}
    </VerticalTimeline>
  );
}