import {
    Chart,
    ArcElement,
    LineElement,
    BarElement,
    PointElement,
    BarController,
//    BubbleController,
    DoughnutController,
    LineController,
    PieController,
//    PolarAreaController,
//    RadarController,
//    ScatterController,
    CategoryScale,
    LinearScale,
//    LogarithmicScale,
//    RadialLinearScale,
//    TimeScale,
//    TimeSeriesScale,
//    Decimation,
//    Filler,
    Legend,
    Title,
    Tooltip,
    Colors,
//    SubTitle
} from 'chart.js';

Chart.register(
    ArcElement,
    LineElement,
    BarElement,
    PointElement,
    BarController,
//    BubbleController,
    DoughnutController,
    LineController,
    PieController,
//    PolarAreaController,
//    RadarController,
//    ScatterController,
    CategoryScale,
    LinearScale,
//    LogarithmicScale,
//    RadialLinearScale,
//    TimeScale,
//    TimeSeriesScale,
//    Decimation,
//    Filler,
    Legend,
    Title,
    Tooltip,
    Colors
//    SubTitle
);

global.Chart = Chart;