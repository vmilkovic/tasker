function HeaderIcon({ Icon, active }) {
  return (
    <div className="flex items-center cursor-pointer sm:px-8 md:px-10 sm:h-12 md:hover:bg-gray-100 rounded-xl active:border-b-2 active:border-blue-500 group">
      <Icon
        className={`h-5 mx-auto text-center text-gray-500 sm:h-6 md:h-7 group-hover:text-blue-500 ${
          active && "text-blue-500"
        }`}
      />
    </div>
  );
}

export default HeaderIcon;
