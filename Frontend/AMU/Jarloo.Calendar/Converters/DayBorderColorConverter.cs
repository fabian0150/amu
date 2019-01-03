/*
    Jarloo
    http://www.jarloo.com
 
    This work is licensed under a Creative Commons Attribution-ShareAlike 3.0 Unported License  
    http://creativecommons.org/licenses/by-sa/3.0/ 

*/

using AMU.Dto;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Windows;
using System.Windows.Data;
using System.Windows.Media;

namespace Jarloo.Calendar.Converters
{
    public class DayBorderColorConverter : IValueConverter
    {
        public object Convert(object value, Type targetType, object parameter, CultureInfo culture)
        {
            List<Appointment> notes = (List < Appointment > )value;

            if (notes.Count == 0) return null;

            if (notes.Count > 0) return new LinearGradientBrush(Color.FromRgb(220, 74, 56), Color.FromRgb(198, 56, 40), new Point(0.5, 0), new Point(0.5, 1));

            return null;
        }

        public object ConvertBack(object value, Type targetType, object parameter, CultureInfo culture)
        {
            throw new NotImplementedException();
        }
    }
}