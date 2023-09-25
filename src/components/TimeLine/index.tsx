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
    date: 'October 2011 - Present',
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
    type: Type.Work,
    date: 'April 2019 - September 2021',
    title: 'System Engineer',
    subtitle: 'Softbank Corp.',
    description: '',
  },
  {
    type: Type.Work,
    date: 'April 2017 - March 2019',
    title: 'Researcher',
    subtitle: 'Institutes of Innovation for Future Society, Nagoya University',
    description: '',
  },
  {
    type: Type.School,
    date: 'September 2014 - September 2019',
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