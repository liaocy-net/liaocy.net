import React from 'react';
import ReactWordcloud from 'react-wordcloud';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/animations/scale.css';

const callbacks = {
  // getWordColor: word => word.value > 50 ? "blue" : "red",
  onWordClick: console.log,
  onWordMouseOver: console.log,
  // getWordTooltip: word => `${word.text} (${word.value}) [${word.value > 50 ? "good" : "bad"}]`,
}
const options = {
  // rotations: 2,
  // rotationAngles: [-90, 0],
};
const size = [600, 400];

const words = [
  { text: 'Java', value: 100 },
  { text: 'Struts', value: 100 },
  { text: 'Spring', value: 100 },
  { text: 'Hibernate', value: 100 },
  { text: 'PHP', value: 100 },
  { text: 'Laravel', value: 90 },
  { text: 'Wordpress', value: 80 },
  { text: 'JavaScript', value: 100 },
  { text: 'NodeJS', value: 90 },
  { text: 'Express', value: 90 },
  { text: 'Python', value: 100 },
  { text: 'Flutter', value: 100 },
  { text: 'Django', value: 90 },
  { text: 'Airflow', value: 80 },
  { text: 'Numpy', value: 80 },
  { text: 'Scipy', value: 80 },
  { text: 'Stable Diffusion', value: 80 },
  { text: 'OpenCV', value: 80 },
  { text: 'VGG', value: 70 },
  { text: 'Yolo', value: 70 },
  { text: 'ssd', value: 70 },
  { text: 'AWS', value: 100 },
  { text: 'EC2', value: 80 },
  { text: 'Lambda', value: 80 },
  { text: 'CloudFront', value: 80 },
  { text: 'S3', value: 80 },
  { text: 'Route 53', value: 80 },
  { text: 'GCP', value: 100 },
  { text: 'BigQuery', value: 80 },
  { text: 'Pub/Sub', value: 80 },
  { text: 'Cloud Function', value: 80 },
  { text: 'Terraform', value: 90 },
  { text: 'Zabbix', value: 80 },
  { text: 'DataDog', value: 80 },
  { text: 'Jupyter', value: 80 },
  { text: 'Pandas', value: 80 },
  { text: 'Tableau', value: 100 },
];

export default function SkillWordCloud() {
  return (
    <ReactWordcloud
      callbacks={callbacks}
      options={options}
      // size={size}
      words={words}
    />
  );
}